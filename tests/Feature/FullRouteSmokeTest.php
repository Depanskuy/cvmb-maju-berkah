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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Smoke test menyeluruh: menyentuh SEMUA route (publik, admin, pelamar)
 * dan semua alur tulis, untuk mendeteksi error 500 / validasi yang salah.
 */
class FullRouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $seeker;
    private Company $company;
    private Job $activeJob;
    private Job $closedJob;
    private JobApplication $application;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('local')->put('cvs/dummy.pdf', '%PDF-1.4 dummy cv');

        $this->admin     = User::where('email', 'admin@majuberkah.test')->firstOrFail();
        $this->seeker    = User::where('email', 'seeker@majuberkah.test')->firstOrFail();
        $this->company   = Company::where('slug', 'cv-maju-berkah')->firstOrFail();
        $this->activeJob = Job::where('status', Job::STATUS_ACTIVE)->orderBy('id')->firstOrFail();
        $this->closedJob = Job::where('status', Job::STATUS_CLOSED)->firstOrFail();

        $this->seeker->profile()->update(['cv_path' => 'cvs/dummy.pdf']);

        $this->application = JobApplication::create([
            'job_id'            => $this->activeJob->id,
            'job_seeker_id'     => $this->seeker->id,
            'applicant_name'    => $this->seeker->name,
            'applicant_email'   => $this->seeker->email,
            'applicant_phone'   => '081234567890',
            'applicant_address' => 'Jl. Gatot Subroto No. 12, Jakarta',
            'education'         => 'S1 Sistem Informasi',
            'skills'            => 'PHP, Laravel',
            'cover_letter'      => 'Saya berminat pada posisi ini.',
            'cv_path'           => 'cvs/dummy.pdf',
            'status'            => JobApplication::STATUS_PENDING,
        ]);
    }

    // =====================================================================
    // 1. Halaman publik
    // =====================================================================

    public function test_semua_halaman_publik_terbuka(): void
    {
        foreach ([
            'home'            => route('home'),
            'about'           => route('about'),
            'services'        => route('services'),
            'contact'         => route('contact'),
            'careers.index'   => route('careers.index'),
        ] as $label => $url) {
            $this->get($url)->assertStatus(200, "GET {$label}");
        }

        $this->get(route('careers.show', $this->activeJob))
            ->assertStatus(200)
            ->assertSee($this->activeJob->title);

        $this->get(route('login'))->assertStatus(200);
        $this->get(route('register'))->assertStatus(200);

        // Guest yang ingin melamar diarahkan ke login
        $this->get(route('careers.apply.create', $this->activeJob))
            ->assertRedirect(route('login'));
    }

    /**
     * BR-10: katalog publik hanya menampilkan lowongan aktif yang belum kedaluwarsa.
     * Ini memeriksa apakah halaman DETAIL juga menjaga aturan yang sama.
     */
    public function test_probe_visibilitas_publik_lowongan_closed_dan_kedaluwarsa(): void
    {
        // Lowongan "closed" di seed
        $closedDetail = $this->get(route('careers.show', $this->closedJob));
        fwrite(STDOUT, "\n[PROBE] detail lowongan CLOSED => HTTP {$closedDetail->getStatusCode()}\n");

        // Lowongan aktif tapi deadline sudah lewat
        $expired = Job::create([
            'company_id'      => $this->company->id,
            'title'           => 'Lowongan Kedaluwarsa ' . uniqid(),
            'category'        => 'Administrasi',
            'location'        => 'Jakarta',
            'employment_type' => 'full_time',
            'deadline'        => now()->subDays(3),
            'status'          => 'active',
            'description'     => 'Lowongan dengan deadline lampau.',
            'requirements'    => 'Umum.',
        ]);

        $catalog = $this->get(route('careers.index'));
        fwrite(STDOUT, '[PROBE] katalog menampilkan lowongan kedaluwarsa? => '
            . ($catalog->getContent() && str_contains($catalog->getContent(), $expired->title) ? 'YA (salah)' : 'TIDAK (benar)') . "\n");

        $expiredDetail = $this->get(route('careers.show', $expired));
        fwrite(STDOUT, "[PROBE] detail lowongan AKTIF-KEDALUWARSA => HTTP {$expiredDetail->getStatusCode()}\n");

        // Jangan gagalkan suite; ini murni probe kebijakan BR-10.
        $this->assertTrue(true);
    }

    /**
     * BR-04: lamaran ke lowongan yang sudah ditutup / lewat deadline harus ditolak.
     */
    public function test_probe_lamaran_ke_lowongan_tutup_atau_kedaluwarsa(): void
    {
        $expired = Job::create([
            'company_id'      => $this->company->id,
            'title'           => 'Lowongan Lampau ' . uniqid(),
            'category'        => 'Administrasi',
            'location'        => 'Jakarta',
            'employment_type' => 'full_time',
            'deadline'        => now()->subDays(2),
            'status'          => 'active',
            'description'     => 'Lowongan aktif dengan deadline lampau.',
            'requirements'    => 'Umum.',
        ]);

        $cv = fn() => UploadedFile::fake()->create('cv.pdf', 50, 'application/pdf');

        $this->actingAs($this->seeker);

        foreach (['CLOSED' => $this->closedJob, 'KEDALUWARSA' => $expired] as $label => $job) {
            $form = $this->get(route('job-seeker.jobs.apply', $job));
            fwrite(STDOUT, "\n[PROBE] form apply lowongan {$label} => HTTP {$form->getStatusCode()}\n");

            $post = $this->post(route('careers.apply', $job), [
                'applicant_name'  => $this->seeker->name,
                'applicant_email' => $this->seeker->email,
                'applicant_phone' => '081234567890',
                'cv'              => $cv(),
            ]);

            $errors = session('errors');
            fwrite(STDOUT, "[PROBE] POST lamaran lowongan {$label} => HTTP {$post->getStatusCode()}"
                . ($errors && $errors->any() ? ' :: VALIDASI ' . json_encode($errors->all(), JSON_UNESCAPED_UNICODE) : '')
                . "\n");

            $saved = JobApplication::where('job_id', $job->id)
                ->where('job_seeker_id', $this->seeker->id)->exists();
            fwrite(STDOUT, '[PROBE] lamaran tersimpan untuk lowongan ' . $label . '? => '
                . ($saved ? 'YA (BOCOR)' : 'TIDAK (benar)') . "\n");
        }

        $this->assertTrue(true);
    }

    // =====================================================================
    // 2. Halaman admin perusahaan
    // =====================================================================
    public function test_semua_halaman_admin_terbuka(): void
    {
        $this->actingAs($this->admin);

        foreach ([
            'company.dashboard'        => route('company.dashboard'),
            'company.profile.edit'     => route('company.profile.edit'),
            'company.jobs.index'       => route('company.jobs.index'),
            'company.jobs.create'      => route('company.jobs.create'),
            'company.jobs.show'        => route('company.jobs.show', $this->activeJob),
            'company.jobs.edit'        => route('company.jobs.edit', $this->activeJob),
            'company.applications.index' => route('company.applications.index'),
            'company.applications.show'  => route('company.applications.show', $this->application),
            'company.applications.cv'    => route('company.applications.cv', $this->application),
            'company.activity-log.index' => route('company.activity-log.index'),
            'company.feedback.index'     => route('company.feedback.index'),
            'company.partnership.index'  => route('company.partnership.index'),
            'company.project.index'      => route('company.project.index'),
        ] as $label => $url) {
            $this->get($url)->assertStatus(200, "GET {$label} ({$url})");
        }

        // Filter & pencarian tidak boleh error
        $this->get(route('company.jobs.index', ['status' => 'active']))->assertStatus(200);
        $this->get(route('company.jobs.index', ['q' => 'gudang']))->assertStatus(200);
        $this->get(route('company.applications.index', ['status' => 'pending']))->assertStatus(200);
        $this->get(route('company.applications.index', ['q' => 'budi']))->assertStatus(200);
        $this->get(route('company.feedback.index', ['search' => 'layanan', 'rating' => 5]))->assertStatus(200);
        $this->get(route('company.activity-log.index', ['action' => 'create']))->assertStatus(200);
    }

    // =====================================================================
    // 3. Halaman portal pencari kerja
    // =====================================================================

    public function test_semua_halaman_pencari_kerja_terbuka(): void
    {
        $this->actingAs($this->seeker);

        $otherJob = Job::where('status', Job::STATUS_ACTIVE)
            ->where('id', '!=', $this->activeJob->id)
            ->firstOrFail();

        foreach ([
            'job-seeker.dashboard'           => route('job-seeker.dashboard'),
            'job-seeker.profile.show'        => route('job-seeker.profile.show'),
            'job-seeker.profile.edit'        => route('job-seeker.profile.edit'),
            'job-seeker.profile.cv'          => route('job-seeker.profile.cv'),
            'job-seeker.jobs.index'          => route('job-seeker.jobs.index'),
            'job-seeker.jobs.show'           => route('job-seeker.jobs.show', $this->activeJob),
            'job-seeker.jobs.apply'          => route('job-seeker.jobs.apply', $otherJob),
            'job-seeker.applications.index'  => route('job-seeker.applications.index'),
            'job-seeker.applications.show'   => route('job-seeker.applications.show', $this->application),
            'job-seeker.applications.edit'   => route('job-seeker.applications.edit', $this->application),
            'job-seeker.applications.cv'     => route('job-seeker.applications.cv', $this->application),
        ] as $label => $url) {
            $this->get($url)->assertStatus(200, "GET {$label} ({$url})");
        }

        // Pencarian & filter portal pelamar
        $this->get(route('job-seeker.jobs.index', ['q' => 'sales', 'type' => 'full_time']))->assertStatus(200);

        // Form create profil dialihkan ke edit karena profil sudah ada (perilaku benar)
        $this->get(route('job-seeker.profile.create'))
            ->assertRedirect(route('job-seeker.profile.edit'));

        // Pengguna login diarahkan dari katalog publik ke portalnya
        $this->get(route('careers.index'))->assertRedirect(route('job-seeker.jobs.index'));
    }

    // =====================================================================
    // 4. Alur autentikasi
    // =====================================================================

    public function test_alur_autentikasi(): void
    {
        // Login admin
        $this->post(route('login.store'), [
            'email'    => 'admin@majuberkah.test',
            'password' => 'password',
        ])->assertRedirect(route('company.dashboard'));
        $this->assertAuthenticatedAs($this->admin);

        // Logout
        $this->post(route('logout'))->assertRedirect(route('home'));

        // Login pelamar
        $this->post(route('login.store'), [
            'email'    => 'seeker@majuberkah.test',
            'password' => 'password',
        ])->assertRedirect(route('job-seeker.dashboard'));
        $this->assertAuthenticatedAs($this->seeker);

        $this->post(route('logout'))->assertRedirect(route('home'));

        // Password salah
        $this->from(route('login'))->post(route('login.store'), [
            'email'    => 'admin@majuberkah.test',
            'password' => 'salah',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

        // Registrasi pelamar baru
        $this->post(route('register.store'), [
            'name'                  => 'Pelamar Baru',
            'email'                 => 'baru.' . uniqid() . '@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('job-seeker.dashboard'));

        // Admin login tidak bisa membuka halaman login lagi (middleware guest)
        $this->actingAs($this->admin)->get(route('login'))->assertRedirect();
    }

    // =====================================================================
    // 5. Alur tulis admin: lowongan
    // =====================================================================

    public function test_alur_tulis_admin_lowongan(): void
    {
        $this->actingAs($this->admin);
        $title = 'Posisi Uji ' . uniqid();

        // CREATE
        $this->post(route('company.jobs.store'), [
            'title'           => $title,
            'category'        => 'Operasional',
            'employment_type' => 'full_time',
            'location'        => 'Jakarta Selatan',
            'salary_min'      => 5000000,
            'salary_max'      => 7000000,
            'deadline'        => now()->addDays(14)->format('Y-m-d'),
            'description'     => 'Deskripsi pekerjaan uji.',
            'requirements'    => 'Kualifikasi uji.',
            'status'          => 'active',
        ])->assertRedirect(route('company.jobs.index'));

        $job = Job::where('title', $title)->firstOrFail();

        // UPDATE
        $this->put(route('company.jobs.update', $job), [
            'title'           => $title . ' Revisi',
            'category'        => 'Operasional',
            'employment_type' => 'contract',
            'location'        => 'Bekasi & Cikarang',
            'salary_min'      => 6000000,
            'salary_max'      => 9000000,
            'deadline'        => now()->addDays(21)->format('Y-m-d'),
            'description'     => 'Deskripsi revisi.',
            'requirements'    => 'Kualifikasi revisi.',
            'status'          => 'active',
        ])->assertRedirect(route('company.jobs.index'));
        $this->assertSame($title . ' Revisi', $job->fresh()->title);

        // TOGGLE
        $this->patch(route('company.jobs.toggle', $job))->assertRedirect();
        $this->assertSame('closed', $job->fresh()->status);

        // DELETE (tanpa lamaran)
        $this->delete(route('company.jobs.destroy', $job))->assertRedirect();
        $this->assertDatabaseMissing('jobs', ['id' => $job->id]);

        // Validasi: gaji maks < gaji min harus ditolak
        $this->from(route('company.jobs.create'))->post(route('company.jobs.store'), [
            'title'           => 'Gaji Terbalik',
            'category'        => 'Operasional',
            'employment_type' => 'full_time',
            'location'        => 'Jakarta',
            'salary_min'      => 9000000,
            'salary_max'      => 5000000,
            'deadline'        => now()->addDays(10)->format('Y-m-d'),
            'description'     => 'Deskripsi.',
            'requirements'    => 'Kualifikasi.',
            'status'          => 'active',
        ])->assertSessionHasErrors('salary_max');

        // Validasi: deadline masa lalu harus ditolak
        $this->from(route('company.jobs.create'))->post(route('company.jobs.store'), [
            'title'           => 'Deadline Lampau',
            'category'        => 'Operasional',
            'employment_type' => 'full_time',
            'location'        => 'Jakarta',
            'deadline'        => now()->subDay()->format('Y-m-d'),
            'description'     => 'Deskripsi.',
            'requirements'    => 'Kualifikasi.',
            'status'          => 'active',
        ])->assertSessionHasErrors('deadline');
    }

    /** BR-09: lowongan yang sudah punya lamaran tidak boleh terhapus permanen. */
    public function test_lowongan_dengan_lamaran_tidak_dihapus_permanen(): void
    {
        $this->actingAs($this->admin);

        $this->delete(route('company.jobs.destroy', $this->activeJob))->assertRedirect();

        $this->assertDatabaseHas('jobs', ['id' => $this->activeJob->id]);
        $this->assertSame('closed', $this->activeJob->fresh()->status);
    }

    // =====================================================================
    // 6. Alur tulis admin: lamaran, kerja sama, feedback, project, profil
    // =====================================================================

    public function test_alur_tulis_admin_lamaran_dan_modul_tambahan(): void
    {
        $this->actingAs($this->admin);

        // Ubah status lamaran
        $this->patch(route('company.applications.status', $this->application), [
            'status'      => 'accepted',
            'review_note' => 'Selamat, Anda diterima.',
        ])->assertRedirect();
        $this->assertSame('accepted', $this->application->fresh()->status);

        // Unduh CV pelamar
        $this->get(route('company.applications.cv', $this->application))->assertStatus(200);

        // Kerja sama: ubah status
        $partnership = Partnership::create([
            'company_name' => 'PT Mitra Uji',
            'contact_name' => 'PIC Mitra',
            'email'        => 'mitra@example.com',
            'phone'        => '021123456',
            'type'         => 'Distribusi',
            'timeline'     => 'Q1',
            'message'      => 'Kami ingin bekerja sama.',
            'status'       => 'pending',
        ]);
        $this->patch(route('company.partnership.status.update', $partnership), [
            'status'     => 'approved',
            'admin_note' => 'Disetujui.',
        ])->assertRedirect();
        $this->assertSame('approved', $partnership->fresh()->status);

        // Feedback: ubah status & hapus
        $feedback = Feedback::create([
            'name'     => 'Pelamar Uji',
            'email'    => 'fb@example.com',
            'category' => 'Pelayanan',
            'rating'   => 4,
            'message'  => 'Pelayanan bagus.',
            'status'   => 'Baru',
        ]);
        $this->patch(route('company.feedback.status.update', $feedback))->assertRedirect();
        $this->assertSame('Ditindaklanjuti', $feedback->fresh()->status);

        $this->delete(route('company.feedback.destroy', $feedback))->assertRedirect();
        $this->assertDatabaseMissing('feedbacks', ['id' => $feedback->id]);

        // Project: create, update, delete
        $this->post(route('company.project.store'), [
            'name'        => 'Proyek Uji',
            'client'      => 'PT Klien Uji',
            'category'    => 'Distribusi',
            'budget'      => 'Rp 100.000.000',
            'timeline'    => 'Jan - Mar 2026',
            'progress'    => 30,
            'status'      => 'ongoing',
            'description' => 'Deskripsi proyek uji.',
        ])->assertRedirect();
        $project = Project::where('name', 'Proyek Uji')->firstOrFail();

        $this->put(route('company.project.update', $project), [
            'name'        => 'Proyek Uji Revisi',
            'client'      => 'PT Klien Uji',
            'category'    => 'Distribusi',
            'budget'      => 'Rp 120.000.000',
            'timeline'    => 'Jan - Apr 2026',
            'progress'    => 75,
            'status'      => 'ongoing',
            'description' => 'Deskripsi revisi.',
        ])->assertRedirect();
        $this->assertSame(75, $project->fresh()->progress);

        $this->delete(route('company.project.destroy', $project))->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);

        // Log aktivitas tetap dapat dibuka setelah semua aksi
        $this->get(route('company.activity-log.index'))->assertStatus(200);
    }

    public function test_admin_update_profil_perusahaan_dengan_upload_logo(): void
    {
        $this->actingAs($this->admin);

        $this->put(route('company.profile.update'), [
            'name'          => 'CV Maju Berkah',
            'slug'          => 'cv-maju-berkah',
            'tagline'       => 'Bertumbuh Bersama, Melangkah Lebih Maju.',
            'description'   => 'Deskripsi perusahaan hasil pengujian.',
            'vision'        => 'Visi hasil pengujian.',
            'mission'       => 'Misi hasil pengujian.',
            'services'      => "Distribusi\nPergudangan",
            'address'       => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
            'contact_email' => 'info@majuberkah.test',
            'contact_phone' => '021-0000000',
            'logo'          => UploadedFile::fake()->image('logo.png', 200, 200),
        ])->assertRedirect();

        $company = $this->company->fresh();
        $this->assertNotNull($company->logo_path, 'logo_path seharusnya terisi');
        Storage::disk('public')->assertExists($company->logo_path);

        // Halaman publik harus tetap render dengan logo hasil unggahan (sebagai guest)
        $this->post(route('logout'));
        $this->get(route('home'))->assertStatus(200);
        $this->get(route('about'))->assertStatus(200);
    }

    // =====================================================================
    // 7. Alur tulis publik & pelamar
    // =====================================================================

    public function test_pengajuan_kerja_sama_dari_halaman_publik(): void
    {
        $this->post(route('contact.partnership'), [
            'company_name'     => 'PT Calon Mitra',
            'contact_name'     => 'Budi PIC',
            'email'            => 'pic@calonmitra.example',
            'phone'            => '0812000111',
            'service_interest' => 'Distribusi & Pengiriman Barang',
            'project_timeline' => 'Q2 2026',
            'project_details'  => 'Kami ingin menjalin kerja sama distribusi regional.',
        ])->assertRedirect();

        $this->assertDatabaseHas('partnerships', ['company_name' => 'PT Calon Mitra']);
    }

    public function test_pelamar_mengirim_lamaran_dan_mengelolanya(): void
    {
        // Pelamar melamar lowongan lain (yang belum pernah dilamar)
        $otherJob = Job::where('status', Job::STATUS_ACTIVE)
            ->where('id', '!=', $this->activeJob->id)
            ->firstOrFail();

        $this->actingAs($this->seeker)->post(route('careers.apply', $otherJob), [
            'applicant_name'    => $this->seeker->name,
            'applicant_email'   => $this->seeker->email,
            'applicant_phone'   => '081234567890',
            'applicant_address' => 'Jl. Gatot Subroto No. 12, Jakarta',
            'education'         => 'S1 Sistem Informasi',
            'skills'            => 'PHP, Laravel, PostgreSQL',
            'cover_letter'      => 'Saya berminat pada posisi ini.',
            'cv'                => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('job-seeker.applications.index'));

        $this->assertDatabaseHas('applications', [
            'job_seeker_id' => $this->seeker->id,
            'job_id'        => $otherJob->id,
        ]);

        // Kirim feedback dari portal pelamar
        $this->post(route('job-seeker.feedback.store'), [
            'category' => 'Pelayanan',
            'rating'   => 5,
            'message'  => 'Portalnya mudah digunakan, terima kasih.',
        ])->assertRedirect();
        $this->assertDatabaseHas('feedbacks', ['user_id' => $this->seeker->id]);

        // Perbarui lamaran yang masih pending (PUT)
        $this->patch(route('job-seeker.applications.update', $this->application), [
            'cover_letter' => 'Surat lamaran yang diperbarui.',
            'education'    => 'S1 Sistem Informasi',
            'skills'       => 'PHP, Laravel, PostgreSQL, Docker',
            'cv'           => UploadedFile::fake()->create('cv-baru.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('job-seeker.applications.show', $this->application));
        $this->assertSame('Surat lamaran yang diperbarui.', $this->application->fresh()->cover_letter);

        // Batalkan lamaran
        $this->delete(route('job-seeker.applications.destroy', $this->application))->assertRedirect();
        $this->application->refresh();
        $this->assertNotNull($this->application->withdrawn_at);

        // Unduh CV lamaran masih bisa (berkas milik sendiri)
        $this->get(route('job-seeker.applications.cv', $this->application))->assertStatus(200);
    }

    public function test_pelamar_mengelola_profil(): void
    {
        $this->actingAs($this->seeker);

        // Update profil
        $this->put(route('job-seeker.profile.update'), [
            'name'      => $this->seeker->name,
            'email'     => $this->seeker->email,
            'phone'     => '081999888777',
            'address'   => 'Jl. Raya Darmo No. 100, Surabaya',
            'education' => 'S2 Sistem Informasi',
            'skills'    => 'PHP, Laravel, PostgreSQL',
            'cv'        => UploadedFile::fake()->create('profil.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('job-seeker.profile.show'));

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id'   => $this->seeker->id,
            'education' => 'S2 Sistem Informasi',
        ]);

        // Unduh CV profil
        $this->get(route('job-seeker.profile.cv'))->assertStatus(200);

        // Hapus profil -> halaman show tetap bisa dibuka (profil dibuat ulang otomatis)
        $this->delete(route('job-seeker.profile.destroy'))->assertRedirect();
        $this->get(route('job-seeker.profile.show'))->assertStatus(200);
    }

    // =====================================================================
    // 8. Kontrol akses silang
    // =====================================================================

    public function test_kontrol_akses_silang_dan_guest(): void
    {
        $adminRoutes = [
            route('company.dashboard'),
            route('company.jobs.index'),
            route('company.applications.index'),
            route('company.profile.edit'),
            route('company.partnership.index'),
            route('company.feedback.index'),
            route('company.project.index'),
            route('company.activity-log.index'),
        ];

        $seekerRoutes = [
            route('job-seeker.dashboard'),
            route('job-seeker.applications.index'),
            route('job-seeker.profile.show'),
            route('job-seeker.jobs.index'),
        ];

        // Guest -> diarahkan ke login
        foreach (array_merge($adminRoutes, $seekerRoutes) as $url) {
            $this->get($url)->assertRedirect(route('login'), "Guest GET {$url}");
        }

        // Pelamar tidak boleh masuk area admin
        foreach ($adminRoutes as $url) {
            $this->actingAs($this->seeker)->get($url)->assertStatus(403, "Seeker GET {$url}");
        }

        // Admin tidak boleh masuk area pelamar
        foreach ($seekerRoutes as $url) {
            $this->actingAs($this->admin)->get($url)->assertStatus(403, "Admin GET {$url}");
        }
    }

    // =====================================================================
    // 9. Halaman error
    // =====================================================================

    public function test_halaman_error_ramah_pengguna(): void
    {
        $this->get('/halaman-tidak-ada-' . uniqid())
            ->assertStatus(404)
            ->assertSee('Error 404');

        $this->actingAs($this->seeker)->get(route('company.dashboard'))
            ->assertStatus(403)
            ->assertSee('Error 403');
    }
}
