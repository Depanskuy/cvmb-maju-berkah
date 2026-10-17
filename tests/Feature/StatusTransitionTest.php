<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Feedback;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Partnership;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Mengunci kontrak transisi status (blueprint §6) dan validasi batas gaji.
 *
 * Aturan yang diuji:
 *  - Lamaran   : pending → processing → accepted|rejected; accepted/rejected FINAL.
 *  - Kerja sama: pending → approved → archived; approved tidak dapat kembali ke pending.
 *  - Feedback  : satu arah Baru → Ditindaklanjuti.
 *  - Project   : planning → ongoing → completed (satu arah, "Selesai" final).
 *  - Gaji      : nilai di luar kapasitas kolom decimal(12,2) ditolak sebagai
 *                kesalahan validasi, bukan error 500.
 */
class StatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    // --- Helper ----------------------------------------------------------

    private function makeAdmin(): User
    {
        return User::create([
            'name'     => 'Admin ' . uniqid(),
            'email'    => 'admin.' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role'     => User::ROLE_ADMIN,
        ]);
    }

    private function makeSeeker(): User
    {
        return User::create([
            'name'     => 'Pelamar ' . uniqid(),
            'email'    => 'pelamar.' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role'     => User::ROLE_SEEKER,
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

    private function makeApplication(array $overrides = []): JobApplication
    {
        $seeker = $this->makeSeeker();

        return JobApplication::create(array_merge([
            'job_seeker_id'   => $seeker->id,
            'job_id'          => $this->makeJob()->id,
            'applicant_name'  => $seeker->name,
            'applicant_email' => $seeker->email,
            'applicant_phone' => '08123456789',
            'cv_path'         => 'cvs/dummy.pdf',
            'status'          => JobApplication::STATUS_PENDING,
        ], $overrides));
    }

    private function makePartnership(string $status = Partnership::STATUS_PENDING): Partnership
    {
        return Partnership::create([
            'company_name' => 'PT Mitra Uji',
            'contact_name' => 'Budi Mitra',
            'email'        => 'mitra@example.com',
            'phone'        => '08123456789',
            'type'         => 'Distribusi Barang',
            'timeline'     => '3 bulan',
            'message'      => 'Pengajuan kerja sama untuk keperluan pengujian.',
            'status'       => $status,
        ]);
    }

    private function makeProject(string $status = Project::STATUS_ONGOING): Project
    {
        return Project::create([
            'name'     => 'Proyek Uji ' . uniqid(),
            'client'   => 'Klien Uji',
            'category' => 'Distribusi',
            'progress' => 50,
            'timeline' => '2026',
            'status'   => $status,
            'budget'   => 'Rp 1.000.000',
        ]);
    }

    private function makeFeedback(string $status = Feedback::STATUS_NEW): Feedback
    {
        return Feedback::create([
            'user_id'  => $this->makeSeeker()->id,
            'name'     => 'Pengulas Uji',
            'email'    => 'pengulas@example.com',
            'category' => 'Portal Website',
            'rating'   => 5,
            'message'  => 'Pelayanan portal sudah sangat baik.',
            'status'   => $status,
        ]);
    }

    // --- Lamaran ---------------------------------------------------------

    public function test_lamaran_pending_dapat_diproses_lalu_diterima(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication();

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $application), [
                'status' => JobApplication::STATUS_PROCESSING,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(JobApplication::STATUS_PROCESSING, $application->fresh()->status);

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $application), [
                'status' => JobApplication::STATUS_ACCEPTED,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(JobApplication::STATUS_ACCEPTED, $application->fresh()->status);
    }

    public function test_lamaran_yang_sudah_diterima_tidak_dapat_dikembalikan_ke_proses(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication(['status' => JobApplication::STATUS_ACCEPTED]);

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $application), [
                'status' => JobApplication::STATUS_PROCESSING,
            ])
            ->assertSessionHas('error');

        $this->assertSame(JobApplication::STATUS_ACCEPTED, $application->fresh()->status);
    }

    public function test_lamaran_yang_sudah_diterima_tidak_dapat_diubah_menjadi_ditolak(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication(['status' => JobApplication::STATUS_ACCEPTED]);

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $application), [
                'status' => JobApplication::STATUS_REJECTED,
            ])
            ->assertSessionHas('error');

        $this->assertSame(JobApplication::STATUS_ACCEPTED, $application->fresh()->status);
    }

    public function test_lamaran_yang_sudah_ditolak_tidak_dapat_diubah_menjadi_diterima(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication(['status' => JobApplication::STATUS_REJECTED]);

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $application), [
                'status' => JobApplication::STATUS_ACCEPTED,
            ])
            ->assertSessionHas('error');

        $this->assertSame(JobApplication::STATUS_REJECTED, $application->fresh()->status);
    }

    public function test_lamaran_final_masih_dapat_diberi_catatan_tanpa_mengubah_status(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication(['status' => JobApplication::STATUS_ACCEPTED]);

        $this->actingAs($admin)
            ->patch(route('company.applications.status', $application), [
                'status'      => JobApplication::STATUS_ACCEPTED,
                'review_note' => 'Selamat! Silakan melapor pada hari Senin pukul 09.00 WIB.',
            ])
            ->assertSessionHasNoErrors();

        $application->refresh();
        $this->assertSame(JobApplication::STATUS_ACCEPTED, $application->status);
        $this->assertSame('Selamat! Silakan melapor pada hari Senin pukul 09.00 WIB.', $application->review_note);
    }

    public function test_halaman_review_menampilkan_papan_keputusan_final(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication(['status' => JobApplication::STATUS_ACCEPTED]);

        $this->actingAs($admin)
            ->get(route('company.applications.show', $application))
            ->assertOk()
            ->assertSee('Keputusan Final')
            ->assertDontSee('Menunggu Review (Pending)');
    }

    public function test_dropdown_status_hanya_menampilkan_status_yang_sah(): void
    {
        $admin       = $this->makeAdmin();
        $application = $this->makeApplication(['status' => JobApplication::STATUS_PROCESSING]);

        $this->actingAs($admin)
            ->get(route('company.applications.show', $application))
            ->assertOk()
            ->assertSee('Sedang Diproses (Screening)')
            ->assertSee('Diterima Bergabung (Accepted)')
            ->assertSee('Tidak Lolos / Ditolak (Rejected)')
            ->assertDontSee('Menunggu Review (Pending)');
    }

    // --- Kerja sama ------------------------------------------------------
    public function test_kerja_sama_pending_dapat_disetujui(): void
    {
        $admin       = $this->makeAdmin();
        $partnership = $this->makePartnership();

        $this->actingAs($admin)
            ->patch(route('company.partnership.status.update', $partnership), [
                'status' => Partnership::STATUS_APPROVED,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(Partnership::STATUS_APPROVED, $partnership->fresh()->status);
    }

    public function test_kerja_sama_yang_sudah_disetujui_tidak_dapat_kembali_ke_menunggu(): void
    {
        $admin       = $this->makeAdmin();
        $partnership = $this->makePartnership(Partnership::STATUS_APPROVED);

        $this->actingAs($admin)
            ->patch(route('company.partnership.status.update', $partnership), [
                'status' => Partnership::STATUS_PENDING,
            ])
            ->assertSessionHas('error');

        $this->assertSame(Partnership::STATUS_APPROVED, $partnership->fresh()->status);
    }

    // --- Feedback --------------------------------------------------------

    public function test_feedback_hanya_dapat_ditandai_ditindaklanjuti_sekali(): void
    {
        $admin    = $this->makeAdmin();
        $feedback = $this->makeFeedback();

        $this->actingAs($admin)
            ->patch(route('company.feedback.status.update', $feedback))
            ->assertSessionHasNoErrors();

        $this->assertSame(Feedback::STATUS_FOLLOWED, $feedback->fresh()->status);

        // Percobaan kedua: ditolak, status tetap final.
        $this->actingAs($admin)
            ->patch(route('company.feedback.status.update', $feedback))
            ->assertSessionHas('error');

        $this->assertSame(Feedback::STATUS_FOLLOWED, $feedback->fresh()->status);
    }

    // --- Project ---------------------------------------------------------

    public function test_project_tidak_dapat_dimundurkan_dari_selesai(): void
    {
        $admin   = $this->makeAdmin();
        $project = $this->makeProject(Project::STATUS_COMPLETED);

        $this->actingAs($admin)
            ->put(route('company.project.update', $project), [
                'name'        => $project->name,
                'client'      => $project->client,
                'category'    => $project->category,
                'budget'      => $project->budget,
                'timeline'    => $project->timeline,
                'progress'    => 40,
                'status'      => Project::STATUS_PLANNING,
                'description' => 'Dikembalikan ke tahap persiapan.',
            ])
            ->assertSessionHasErrors('status');

        $project->refresh();
        $this->assertSame(Project::STATUS_COMPLETED, $project->status);
        $this->assertSame(50, $project->progress);
    }

    public function test_project_berjalan_dapat_diselesaikan(): void
    {
        $admin   = $this->makeAdmin();
        $project = $this->makeProject(Project::STATUS_ONGOING);

        $this->actingAs($admin)
            ->put(route('company.project.update', $project), [
                'name'        => $project->name,
                'client'      => $project->client,
                'category'    => $project->category,
                'budget'      => $project->budget,
                'timeline'    => $project->timeline,
                'progress'    => 100,
                'status'      => Project::STATUS_COMPLETED,
                'description' => 'Pekerjaan selesai.',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(Project::STATUS_COMPLETED, $project->fresh()->status);
    }

    // --- Validasi batas gaji (pencegah error 500 numeric overflow) -------

    public function test_gaji_di_luar_kapasitas_kolom_ditolak_sebagai_validasi(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('company.jobs.store'), [
            'title'           => 'Lowongan Gaji Ekstrem',
            'category'        => 'Umum',
            'employment_type' => 'full_time',
            'location'        => 'Jakarta',
            'salary_min'      => 20000000000,
            'salary_max'      => 299991100000,
            'deadline'        => now()->addDays(10)->toDateString(),
            'description'     => 'Deskripsi lowongan uji.',
            'requirements'    => 'Persyaratan lowongan uji.',
            'status'          => 'active',
        ]);

        $this->assertSame(302, $response->getStatusCode());
        $response->assertSessionHasErrors(['salary_min', 'salary_max']);
        $this->assertSame(0, Job::where('title', 'Lowongan Gaji Ekstrem')->count());
    }

    public function test_gaji_pada_batas_maksimum_tetap_dapat_disimpan(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->post(route('company.jobs.store'), [
            'title'           => 'Lowongan Gaji Batas Maksimum',
            'category'        => 'Umum',
            'employment_type' => 'full_time',
            'location'        => 'Jakarta',
            'salary_min'      => 1000000000,
            'salary_max'      => Job::SALARY_MAX_ALLOWED,
            'deadline'        => now()->addDays(10)->toDateString(),
            'description'     => 'Deskripsi lowongan uji.',
            'requirements'    => 'Persyaratan lowongan uji.',
            'status'          => 'active',
        ])->assertSessionHasNoErrors();

        $this->assertSame(1, Job::where('title', 'Lowongan Gaji Batas Maksimum')->count());
    }
}
