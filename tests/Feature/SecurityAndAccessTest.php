<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Menguji pemisahan akses role (Admin vs Pelamar), kepemilikan data (IDOR),
 * filter lowongan, alur ubah lamaran, update status, dan profil perusahaan.
 */
class SecurityAndAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeSeeker(?string $email = null): User
    {
        return User::create([
            'name'     => 'Pelamar ' . uniqid(),
            'email'    => $email ?? ('pelamar.' . uniqid() . '@example.com'),
            'password' => bcrypt('password'),
            'role'     => User::ROLE_SEEKER,
        ]);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'name'     => 'Admin ' . uniqid(),
            'email'    => 'admin.' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role'     => User::ROLE_ADMIN,
        ]);
    }

    private function makeJob(array $overrides = []): Job
    {
        return Job::create(array_merge([
            'company_id'      => Company::current()->id,
            'title'           => 'Posisi ' . uniqid(),
            'category'        => 'Umum',
            'location'        => 'Jakarta',
            'employment_type' => 'full_time',
            'deadline'        => now()->addDays(10),
            'status'          => 'active',
            'description'     => 'Deskripsi lowongan uji.',
            'requirements'    => 'Persyaratan lowongan uji.',
        ], $overrides));
    }

    private function makeApplication(User $seeker, Job $job, array $overrides = []): JobApplication
    {
        return JobApplication::create(array_merge([
            'job_seeker_id'          => $seeker->id,
            'job_id'   => $job->id,
            'applicant_name'   => $seeker->name,
            'applicant_email'  => $seeker->email,
            'applicant_phone'  => '08123456789',
            'cv_path'          => 'cvs/dummy.pdf',
            'status'           => JobApplication::STATUS_PENDING,
        ], $overrides));
    }

    // --- Keamanan akses dasar ---------------------------------------------

    public function test_guest_is_redirected_to_login_from_protected_areas(): void
    {
        $this->get(route('job-seeker.dashboard'))->assertRedirect(route('login'));
        $this->get(route('company.dashboard'))->assertRedirect(route('login'));
    }

    public function test_seeker_cannot_access_admin_area(): void
    {
        $seeker = $this->makeSeeker();

        $this->actingAs($seeker)->get(route('company.dashboard'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('company.jobs.index'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('company.applications.index'))->assertStatus(403);
    }

    public function test_admin_cannot_access_seeker_area(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('job-seeker.dashboard'))->assertStatus(403);
        $this->actingAs($admin)->get(route('job-seeker.applications.index'))->assertStatus(403);
    }

    public function test_seeker_cannot_act_on_another_seekers_application(): void
    {
        $owner = $this->makeSeeker();
        $other = $this->makeSeeker();
        $job   = $this->makeJob();
        $app   = $this->makeApplication($owner, $job);

        $this->actingAs($other)->get(route('job-seeker.applications.show', $app))->assertStatus(403);
        $this->actingAs($other)->get(route('job-seeker.applications.edit', $app))->assertStatus(403);
        $this->actingAs($other)->put(route('job-seeker.applications.update', $app), [])->assertStatus(403);
        $this->actingAs($other)->delete(route('job-seeker.applications.destroy', $app))->assertStatus(403);
    }

    public function test_guest_cannot_submit_application(): void
    {
        $job = $this->makeJob();

        $this->post(route('careers.apply', $job), [])->assertRedirect(route('login'));
    }

    // --- Aturan bisnis lamaran --------------------------------------------

    public function test_withdrawn_application_does_not_block_reapplying(): void
    {
        Storage::fake('local');

        $seeker = $this->makeSeeker();
        $job    = $this->makeJob();

        // Lamaran lama yang sudah ditarik tidak boleh menghalangi lamaran baru.
        $this->makeApplication($seeker, $job, ['withdrawn_at' => now()]);

        $cv = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

        $response = $this->actingAs($seeker)->post(route('careers.apply', $job), [
            'applicant_name'  => $seeker->name,
            'applicant_email' => $seeker->email,
            'applicant_phone' => '08123456789',
            'cv'              => $cv,
        ]);

        $response->assertRedirect(route('job-seeker.applications.index'));

        $this->assertEquals(
            2,
            JobApplication::where('job_id', $job->id)->where('job_seeker_id', $seeker->id)->count()
        );
    }

    public function test_application_can_only_be_edited_while_pending(): void
    {
        $seeker = $this->makeSeeker();
        $job    = $this->makeJob();
        $app    = $this->makeApplication($seeker, $job, ['status' => JobApplication::STATUS_PROCESSING]);

        $this->actingAs($seeker)
            ->get(route('job-seeker.applications.edit', $app))
            ->assertRedirect(route('job-seeker.applications.show', $app));
    }

    public function test_pending_application_can_be_edited_by_owner(): void
    {
        Storage::fake('local');

        $seeker = $this->makeSeeker();
        $job    = $this->makeJob();
        $app    = $this->makeApplication($seeker, $job);

        $this->actingAs($seeker)->get(route('job-seeker.applications.edit', $app))->assertStatus(200);

        $response = $this->actingAs($seeker)->put(route('job-seeker.applications.update', $app), [
            'education'    => 'S1 Manajemen',
            'skills'       => 'Negosiasi, Distribusi',
            'cover_letter' => 'Saya berminat bergabung.',
        ]);

        $response->assertRedirect(route('job-seeker.applications.show', $app));

        $app->refresh();
        $this->assertEquals('S1 Manajemen', $app->education);
        $this->assertEquals('Negosiasi, Distribusi', $app->skills);
    }

    // --- Update status oleh admin -----------------------------------------

    public function test_admin_can_update_application_status_but_not_legacy_interview_status(): void
    {
        $admin  = $this->makeAdmin();
        $seeker = $this->makeSeeker();
        $job    = $this->makeJob();
        $app    = $this->makeApplication($seeker, $job);

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $app), ['status' => JobApplication::STATUS_ACCEPTED])
            ->assertRedirect();
        $this->assertEquals(JobApplication::STATUS_ACCEPTED, $app->fresh()->status);

        // 'interview' bukan nilai status yang sah menurut kontrak blueprint.
        $this->actingAs($admin)
            ->patch(route('company.applications.status', $app), ['status' => 'interview'])
            ->assertSessionHasErrors('status');
    }

    public function test_admin_cannot_change_status_of_withdrawn_application(): void
    {
        $admin  = $this->makeAdmin();
        $seeker = $this->makeSeeker();
        $job    = $this->makeJob();
        $app    = $this->makeApplication($seeker, $job, ['withdrawn_at' => now()]);

        $response = $this->actingAs($admin)
            ->patch(route('company.applications.status', $app), ['status' => JobApplication::STATUS_ACCEPTED]);

        $response->assertSessionHas('error');
        $this->assertNotEquals(JobApplication::STATUS_ACCEPTED, $app->fresh()->status);
    }

    // --- Filter & pencarian lowongan --------------------------------------

    public function test_seeker_job_search_and_filters_return_matching_jobs(): void
    {
        $tokenA = 'Gudang' . uniqid();
        $tokenB = 'Sales' . uniqid();

        $this->makeJob(['title' => $tokenA, 'category' => 'Distribusi', 'location' => 'Bandung']);
        $this->makeJob(['title' => $tokenB, 'category' => 'Pemasaran', 'location' => 'Surabaya']);

        $seeker = $this->makeSeeker();

        // Pencarian kata kunci
        $this->actingAs($seeker)->get(route('job-seeker.jobs.index', ['q' => $tokenA]))
            ->assertStatus(200)->assertSee($tokenA)->assertDontSee($tokenB);

        // Filter kategori
        $this->actingAs($seeker)->get(route('job-seeker.jobs.index', ['category' => 'Pemasaran']))
            ->assertStatus(200)->assertSee($tokenB)->assertDontSee($tokenA);

        // Filter lokasi
        $this->actingAs($seeker)->get(route('job-seeker.jobs.index', ['location' => 'Bandung']))
            ->assertStatus(200)->assertSee($tokenA)->assertDontSee($tokenB);

        // Urutan berdasarkan deadline terdekat
        $this->actingAs($seeker)->get(route('job-seeker.jobs.index', ['sort' => 'deadline']))
            ->assertStatus(200);
    }

    public function test_public_career_catalog_supports_filters(): void
    {
        $token = 'Kasir' . uniqid();
        $this->makeJob(['title' => $token, 'category' => 'Distribusi', 'location' => 'Medan']);

        $this->get(route('careers.index', ['q' => $token]))
            ->assertStatus(200)->assertSee($token);

        $this->get(route('careers.index', ['location' => 'Medan']))
            ->assertStatus(200);
    }

    // --- Profil perusahaan (admin) ----------------------------------------

    public function test_admin_can_update_companies_contact_fields(): void
    {
        $admin   = $this->makeAdmin();
        $company = Company::current();

        $response = $this->actingAs($admin)->put(route('company.profile.update'), [
            'name'          => 'CV Maju Berkah',
            'slug'          => $company->slug,
            'description'   => 'Perusahaan distribusi barang dan penyediaan kebutuhan usaha.',
            'contact_email' => 'info@majuberkah.test',
            'contact_phone' => '021-1234567',
        ]);

        $response->assertRedirect();

        $company->refresh();
        $this->assertEquals('info@majuberkah.test', $company->contact_email);
        $this->assertEquals('021-1234567', $company->contact_phone);
    }

    public function test_seeker_cannot_update_companies(): void
    {
        $seeker = $this->makeSeeker();

        $this->actingAs($seeker)
            ->put(route('company.profile.update'), ['name' => 'Diretas'])
            ->assertStatus(403);
    }
}
