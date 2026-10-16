<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CareerApplicationTest extends TestCase
{
    private function getActiveJob(): JobListing
    {
        $job = JobListing::where('status', 'active')
            ->whereDate('deadline', '>=', now())
            ->first();

        if (! $job) {
            $job = JobListing::create([
                'title'           => 'Arsitek Senior ' . uniqid(),
                'category'        => 'Teknik & Konstruksi',
                'location'        => 'Jakarta Pusat',
                'employment_type' => 'full_time',
                'deadline'        => now()->addDays(14),
                'status'          => 'active',
                'description'     => 'Deskripsi pekerjaan arsitek senior.',
                'requirements'    => 'Minimal S1 Arsitektur dengan pengalaman 3 tahun.',
            ]);
        }

        return $job;
    }

    private function createSeeker(?string $email = null): User
    {
        $email = $email ?? 'seeker.' . uniqid() . '@example.com';

        $user = User::create([
            'name'     => 'Kandidat ' . uniqid(),
            'email'    => $email,
            'password' => bcrypt('password'),
            'role'     => User::ROLE_SEEKER,
        ]);

        $user->jobSeekerProfile()->create([
            'phone'     => '081298765432',
            'education' => 'S1 Teknik Sipil',
            'address'   => 'Jakarta',
            'skills'    => 'AutoCAD, SAP2000, Manajemen Proyek',
        ]);

        return $user;
    }

    private function createAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin_test@majusukses.test'],
            [
                'name'     => 'Super Admin Test',
                'password' => bcrypt('password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );
    }

    // 1. Publik melihat katalog lowongan tanpa login
    public function test_public_visitor_can_browse_careers_catalog_and_view_job_detail_without_login(): void
    {
        $job = $this->getActiveJob();

        $responseList = $this->get(route('careers.index'));
        $responseList->assertStatus(200);
        $responseList->assertSee($job->title);

        $responseDetail = $this->get(route('careers.show', $job));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($job->title);
        $responseDetail->assertSee('Lamar Sekarang');
    }

    // 2. Pengunjung belum login diarahkan ke login saat ingin melamar
    public function test_guest_is_redirected_to_login_with_intended_url_when_clicking_apply(): void
    {
        $job = $this->getActiveJob();

        $response = $this->get(route('careers.apply.create', $job));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info');
        $this->assertEquals(route('careers.apply.create', $job), session('url.intended'));
    }

    // 3. Registrasi pencari kerja berhasil dan diarahkan ke intended URL
    public function test_guest_can_register_as_job_seeker_and_is_redirected_to_intended_page(): void
    {
        $job = $this->getActiveJob();
        $email = 'newseeker.' . uniqid() . '@example.com';

        // Simulasikan intended URL
        $response = $this->withSession(['url.intended' => route('careers.apply.create', $job)])
            ->post(route('register.store'), [
                'name'                  => 'Budi Baru',
                'email'                 => $email,
                'password'              => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect(route('careers.apply.create', $job));
        $this->assertAuthenticated();

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_SEEKER, $user->role);
        $this->assertNotNull($user->jobSeekerProfile);
    }

    // 4. Admin perusahaan dilarang melamar lowongan
    public function test_company_admin_is_forbidden_from_applying_to_jobs(): void
    {
        $admin = $this->createAdmin();
        $job = $this->getActiveJob();

        // Admin buka form lamaran -> 403
        $responseGet = $this->actingAs($admin)->get(route('careers.apply.create', $job));
        $responseGet->assertStatus(403);

        // Admin kirim POST lamaran -> 403
        $cvFile = UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf');
        $responsePost = $this->actingAs($admin)->post(route('careers.apply.store', $job), [
            'applicant_name'  => $admin->name,
            'applicant_email' => $admin->email,
            'applicant_phone' => '08123456789',
            'cv'              => $cvFile,
        ]);
        $responsePost->assertStatus(403);
    }

    // 5. Pencari kerja login dapat melihat form lamaran dengan email terkunci
    public function test_job_seeker_can_view_apply_form_with_locked_account_email(): void
    {
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        $response = $this->actingAs($seeker)->get(route('careers.apply.create', $job));

        $response->assertStatus(200);
        $response->assertSee('Form Lamaran Pekerjaan');
        $response->assertSee($job->title);
        $response->assertSee($seeker->email);
        $response->assertSee('readonly', false);
    }

    // 6. Pencari kerja berhasil mengirim lamaran terhubung user_id
    public function test_job_seeker_submits_application_linked_to_their_user_id_and_redirects_to_lamaran_saya(): void
    {
        Storage::fake('local');
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();
        $cvFile = UploadedFile::fake()->create('curriculum_vitae.pdf', 300, 'application/pdf');

        $response = $this->actingAs($seeker)->post(route('careers.apply.store', $job), [
            'applicant_name'    => $seeker->name,
            'applicant_email'   => $seeker->email,
            'applicant_phone'   => '081234567890',
            'applicant_address' => 'Jl. Sudirman No. 10 Jakarta',
            'education'         => 'S1 Teknik Arsitektur',
            'skills'            => 'AutoCAD, Revit, 3ds Max',
            'cover_letter'      => 'Saya ingin bergabung dengan tim konstruksi CV Maju Sukses.',
            'cv'                => $cvFile,
            'consent'           => '1',
        ]);

        $response->assertRedirect(route('seeker.applications'));
        $response->assertSessionHas('apply_success');

        $this->assertDatabaseHas('applications', [
            'user_id'         => $seeker->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => $seeker->name,
            'applicant_email' => $seeker->email,
            'status'          => 'pending',
        ]);
    }

    // 7. Cegah lamaran duplikat untuk posisi yang sama
    public function test_duplicate_application_by_same_seeker_is_prevented(): void
    {
        Storage::fake('local');
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        // Lamaran pertama
        Application::create([
            'user_id'         => $seeker->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => $seeker->name,
            'applicant_email' => $seeker->email,
            'applicant_phone' => '081234567890',
            'cv_path'         => 'cvs/dummy.pdf',
            'status'          => 'pending',
        ]);

        $cvFile = UploadedFile::fake()->create('curriculum_vitae.pdf', 300, 'application/pdf');
        $applyUrl = route('careers.apply.create', $job);

        $response = $this->actingAs($seeker)
            ->from($applyUrl)
            ->post(route('careers.apply.store', $job), [
                'applicant_name'  => $seeker->name,
                'applicant_email' => $seeker->email,
                'applicant_phone' => '081234567890',
                'cv'              => $cvFile,
            ]);

        $response->assertRedirect($applyUrl);
        $response->assertSessionHasErrors(['applicant_email']);
    }

    // 8. Proteksi manipulasi email (anti-impersonation)
    public function test_seeker_cannot_impersonate_another_account_email_when_submitting(): void
    {
        Storage::fake('local');
        $seeker = $this->createSeeker();
        $victimEmail = 'victim.' . uniqid() . '@example.com';
        $job = $this->getActiveJob();
        $cvFile = UploadedFile::fake()->create('curriculum_vitae.pdf', 300, 'application/pdf');

        // Form mencoba mengirim email korban
        $response = $this->actingAs($seeker)->post(route('careers.apply.store', $job), [
            'applicant_name'  => 'Hacker Name',
            'applicant_email' => $victimEmail,
            'applicant_phone' => '081234567890',
            'cv'              => $cvFile,
        ]);

        $response->assertRedirect(route('seeker.applications'));

        // Database HARUS tetap menyimpan email asli akun yang login, bukan email victim
        $this->assertDatabaseHas('applications', [
            'user_id'         => $seeker->id,
            'job_listing_id'  => $job->id,
            'applicant_email' => $seeker->email,
        ]);
        $this->assertDatabaseMissing('applications', [
            'applicant_email' => $victimEmail,
        ]);
    }

    // 9. Menu "Lamaran Saya" hanya menampilkan lamaran milik pengguna yang login
    public function test_lamaran_saya_only_shows_applications_belonging_to_the_authenticated_seeker(): void
    {
        $seekerA = $this->createSeeker();
        $seekerB = $this->createSeeker();
        $job = $this->getActiveJob();

        $appA = Application::create([
            'user_id'         => $seekerA->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => 'Pelamar A Rahasia',
            'applicant_email' => $seekerA->email,
            'applicant_phone' => '08111111111',
            'cv_path'         => 'cvs/dummyA.pdf',
            'status'          => 'pending',
        ]);

        $appB = Application::create([
            'user_id'         => $seekerB->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => 'Pelamar B Rahasia',
            'applicant_email' => $seekerB->email,
            'applicant_phone' => '08222222222',
            'cv_path'         => 'cvs/dummyB.pdf',
            'status'          => 'pending',
        ]);

        $response = $this->actingAs($seekerA)->get(route('seeker.applications'));

        $response->assertStatus(200);
        $response->assertSee('Pelamar A Rahasia');
        $response->assertDontSee('Pelamar B Rahasia');
    }

    // 10. Otorisasi kepemilikan lamaran: Seeker tidak boleh melihat / membatalkan lamaran orang lain
    public function test_seeker_cannot_view_or_withdraw_application_of_another_user(): void
    {
        $seekerA = $this->createSeeker();
        $seekerB = $this->createSeeker();
        $job = $this->getActiveJob();

        $appB = Application::create([
            'user_id'         => $seekerB->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => 'Pelamar B',
            'applicant_email' => $seekerB->email,
            'applicant_phone' => '08222222222',
            'cv_path'         => 'cvs/dummyB.pdf',
            'status'          => 'pending',
        ]);

        // Seeker A buka detail lamaran Seeker B -> 403
        $responseShow = $this->actingAs($seekerA)->get(route('seeker.applications.show', $appB));
        $responseShow->assertStatus(403);

        // Seeker A batalkan lamaran Seeker B -> 403
        $responseWithdraw = $this->actingAs($seekerA)->delete(route('seeker.applications.withdraw', $appB));
        $responseWithdraw->assertStatus(403);
    }

    // 11. Pencari kerja dapat membatalkan lamarannya sendiri yang masih pending
    public function test_seeker_can_withdraw_their_own_pending_application(): void
    {
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        $app = Application::create([
            'user_id'         => $seeker->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => $seeker->name,
            'applicant_email' => $seeker->email,
            'applicant_phone' => '081234567890',
            'cv_path'         => 'cvs/dummy.pdf',
            'status'          => 'pending',
        ]);

        $response = $this->actingAs($seeker)->delete(route('seeker.applications.withdraw', $app));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $app->refresh();
        $this->assertTrue($app->isWithdrawn());
        $this->assertEquals('Dibatalkan', $app->status_label);
    }

    // 12. Admin perusahaan dapat CRUD lowongan kerja
    public function test_admin_can_manage_job_listings_crud(): void
    {
        $admin = $this->createAdmin();

        // 1. Dashboard
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200);

        // 2. Create Lowongan
        $title = 'Mandor Proyek Lapangan ' . uniqid();
        $responseCreate = $this->actingAs($admin)->post(route('admin.lowongan.store'), [
            'title'           => $title,
            'category'        => 'Operasional Lapangan',
            'employment_type' => 'full_time',
            'location'        => 'Bekasi',
            'deadline'        => now()->addDays(20)->format('Y-m-d'),
            'description'     => 'Memimpin tim tukang di proyek perumahan.',
            'requirements'    => 'Pengalaman minimal 5 tahun sebagai mandor.',
            'status'          => 'active',
        ]);
        $responseCreate->assertRedirect(route('admin.lowongan.index'));

        $job = JobListing::where('title', $title)->first();
        $this->assertNotNull($job);

        // 3. Update Lowongan
        $responseUpdate = $this->actingAs($admin)->put(route('admin.lowongan.update', $job), [
            'title'           => $title . ' (Revisi)',
            'category'        => 'Operasional Lapangan',
            'employment_type' => 'contract',
            'location'        => 'Bekasi & Cikarang',
            'deadline'        => now()->addDays(25)->format('Y-m-d'),
            'description'     => 'Deskripsi revisi.',
            'requirements'    => 'Kualifikasi revisi.',
            'status'          => 'active',
        ]);
        $responseUpdate->assertRedirect(route('admin.lowongan.index'));
        $job->refresh();
        $this->assertEquals($title . ' (Revisi)', $job->title);

        // 4. Toggle Status
        $this->actingAs($admin)->patch(route('admin.lowongan.toggle', $job));
        $job->refresh();
        $this->assertEquals('closed', $job->status);

        // 5. Delete Lowongan
        $this->actingAs($admin)->delete(route('admin.lowongan.destroy', $job));
        $this->assertDatabaseMissing('job_listings', ['id' => $job->id]);
    }

    // 13. Admin dapat melihat lamaran masuk dan memperbarui status yang langsung tercermin di portal Seeker
    public function test_admin_can_update_application_status_and_it_reflects_in_seeker_portal(): void
    {
        $admin = $this->createAdmin();
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        $app = Application::create([
            'user_id'         => $seeker->id,
            'job_listing_id'  => $job->id,
            'applicant_name'  => $seeker->name,
            'applicant_email' => $seeker->email,
            'applicant_phone' => '081234567890',
            'cv_path'         => 'cvs/dummy.pdf',
            'status'          => 'pending',
        ]);

        // Admin lihat daftar lamaran
        $this->actingAs($admin)->get(route('admin.lamaran.index'))->assertStatus(200);

        // Admin buka detail lamaran
        $this->actingAs($admin)->get(route('admin.lamaran.show', $app))->assertStatus(200);

        // Admin ubah status menjadi accepted dan beri catatan review
        $responseUpdate = $this->actingAs($admin)->patch(route('admin.lamaran.status.update', $app), [
            'status'      => 'accepted',
            'review_note' => 'Selamat! Anda diterima sebagai kandidat terpilih. Harap hadir tgl 1.',
        ]);
        $responseUpdate->assertSessionHas('success');

        $app->refresh();
        $this->assertEquals('accepted', $app->status);
        $this->assertEquals('Selamat! Anda diterima sebagai kandidat terpilih. Harap hadir tgl 1.', $app->review_note);

        // Seeker login dan lihat Lamaran Saya
        $responseSeeker = $this->actingAs($seeker)->get(route('seeker.applications'));
        $responseSeeker->assertStatus(200);
        $responseSeeker->assertSee('Diterima');
        $responseSeeker->assertSee('Selamat! Anda diterima sebagai kandidat terpilih. Harap hadir tgl 1.');
    }

    // 14. Pencari kerja dilarang mengakses panel admin (403)
    public function test_job_seeker_is_forbidden_from_admin_portal(): void
    {
        $seeker = $this->createSeeker();

        $this->actingAs($seeker)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('admin.lowongan.index'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('admin.lamaran.index'))->assertStatus(403);
    }

    // 15. Custom error pages tampil tanpa error bawaan Laravel
    public function test_custom_error_pages_render_without_laravel_defaults(): void
    {
        // 404 test
        $response404 = $this->get('/halaman-lowongan-pasti-tidak-ada-12345');
        $response404->assertStatus(404);
        $response404->assertSee('Halaman Tidak Ditemukan');
        $response404->assertSee('Error 404');

        // 403 test
        $seeker = $this->createSeeker();
        $response403 = $this->actingAs($seeker)->get(route('admin.dashboard'));
        $response403->assertStatus(403);
        $response403->assertSee('Akses Ditolak');
        $response403->assertSee('Error 403');
    }

    // 16. CRUD Lengkap Profil oleh Job Seeker (Create, Read, Update, Delete)
    public function test_seeker_can_perform_full_profile_crud_operations(): void
    {
        Storage::fake('local');

        // Buat user seeker tanpa profile terlebih dahulu
        $seeker = User::create([
            'name'     => 'Kandidat CRUD ' . uniqid(),
            'email'    => 'crudseeker.' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role'     => User::ROLE_SEEKER,
        ]);

        // --- 1. CREATE ---
        // Buka form create
        $responseCreateForm = $this->actingAs($seeker)->get(route('seeker.profile.create'));
        $responseCreateForm->assertStatus(200);
        $responseCreateForm->assertSee('Buat Profil Pencari Kerja');

        // Simpan data profil baru
        $cvFile = UploadedFile::fake()->create('profil_cv.pdf', 300, 'application/pdf');
        $responseStore = $this->actingAs($seeker)->post(route('seeker.profile.store'), [
            'phone'     => '08123456789',
            'address'   => 'Jl. Pahlawan No. 7, Surabaya',
            'education' => 'S1 Teknik Industri',
            'skills'    => 'Supply Chain, Lean Six Sigma',
            'cv'        => $cvFile,
        ]);
        $responseStore->assertRedirect(route('seeker.profile.show'));
        $responseStore->assertSessionHas('success');

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id'   => $seeker->id,
            'phone'     => '08123456789',
            'education' => 'S1 Teknik Industri',
        ]);

        // --- 2. READ ---
        $responseShow = $this->actingAs($seeker)->get(route('seeker.profile.show'));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('S1 Teknik Industri');
        $responseShow->assertSee('Supply Chain, Lean Six Sigma');
        $responseShow->assertSee('Jl. Pahlawan No. 7, Surabaya');

        // --- 3. UPDATE ---
        $responseEditForm = $this->actingAs($seeker)->get(route('seeker.profile.edit'));
        $responseEditForm->assertStatus(200);

        $responseUpdate = $this->actingAs($seeker)->put(route('seeker.profile.update'), [
            'name'      => $seeker->name . ' M.T.',
            'email'     => $seeker->email,
            'phone'     => '081999888777',
            'address'   => 'Jl. Raya Darmo No. 100, Surabaya',
            'education' => 'S2 Teknik Industri',
            'skills'    => 'Supply Chain, Lean Six Sigma, ERP SAP',
        ]);
        $responseUpdate->assertRedirect(route('seeker.profile.show'));
        $responseUpdate->assertSessionHas('success');

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id'   => $seeker->id,
            'phone'     => '081999888777',
            'education' => 'S2 Teknik Industri',
            'skills'    => 'Supply Chain, Lean Six Sigma, ERP SAP',
        ]);

        // --- 4. DELETE ---
        $responseDelete = $this->actingAs($seeker)->delete(route('seeker.profile.destroy'));
        $responseDelete->assertRedirect(route('seeker.profile.show'));
        $responseDelete->assertSessionHas('success');

        $this->assertDatabaseMissing('job_seeker_profiles', [
            'user_id' => $seeker->id,
        ]);
    }

    // 17. Admin perusahaan dilarang mengakses portal pencari kerja (Akses Silang)
    public function test_company_admin_is_forbidden_from_seeker_portal(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->get(route('seeker.dashboard'))->assertStatus(403);
        $this->actingAs($admin)->get(route('seeker.applications'))->assertStatus(403);
        $this->actingAs($admin)->get(route('seeker.profile.show'))->assertStatus(403);
        $this->actingAs($admin)->get(route('seeker.profile.edit'))->assertStatus(403);
    }

    // 18. Pencari kerja dapat melihat dashboard pencari kerja miliknya
    public function test_job_seeker_can_view_seeker_dashboard(): void
    {
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        $response = $this->actingAs($seeker)->get(route('seeker.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Portal Pelamar');
        $response->assertSee($seeker->name);
        $response->assertSee('Total Lamaran');
        $response->assertSee('Menunggu Review');
    }

    // 19. Admin dapat melihat halaman detail lowongan kerja (admin.lowongan.show)
    public function test_admin_can_view_job_listing_detail(): void
    {
        $admin = $this->createAdmin();
        $job = $this->getActiveJob();

        $response = $this->actingAs($admin)->get(route('admin.lowongan.show', $job));
        $response->assertStatus(200);
        $response->assertSee($job->title);
        $response->assertSee('Deskripsi Pekerjaan');
        $response->assertSee('Kualifikasi &amp; Persyaratan', false);
    }

    // 20. Pencari kerja dilarang mengakses halaman detail lowongan admin
    public function test_seeker_is_forbidden_from_admin_job_listing_detail(): void
    {
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        $response = $this->actingAs($seeker)->get(route('admin.lowongan.show', $job));
        $response->assertStatus(403);
    }

    // 21. Pengguna login dialihkan dari beranda publik ke dashboard masing-masing
    public function test_authenticated_users_are_redirected_from_landing_page_to_role_dashboard(): void
    {
        $seeker = $this->createSeeker();
        $admin = $this->createAdmin();

        $this->actingAs($seeker)->get(route('home'))->assertRedirect(route('seeker.dashboard'));
        $this->actingAs($admin)->get(route('home'))->assertRedirect(route('admin.dashboard'));
    }

    // 22. Pencari kerja dapat menjelajahi lowongan di dalam portal pelamar (tanpa keluar ke publik)
    public function test_seeker_can_browse_and_view_jobs_inside_portal(): void
    {
        $seeker = $this->createSeeker();
        $job = $this->getActiveJob();

        // Katalog lowongan portal pelamar
        $responseIndex = $this->actingAs($seeker)->get(route('seeker.jobs'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee($job->title);

        // Detail lowongan portal pelamar
        $responseShow = $this->actingAs($seeker)->get(route('seeker.jobs.show', $job));
        $responseShow->assertStatus(200);
        $responseShow->assertSee($job->title);

        // Form lamar lowongan portal pelamar
        $responseApply = $this->actingAs($seeker)->get(route('seeker.jobs.apply', $job));
        $responseApply->assertStatus(200);
        $responseApply->assertSee($job->title);
    }

    // 23. Admin dapat mengakses seluruh menu sidebar (Kerja Sama, Feedback, Project, Pengaturan)
    public function test_admin_can_access_all_sidebar_modules(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->get(route('admin.kerja-sama.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.feedback.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.project.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.pengaturan.index'))->assertStatus(200);
    }
}
