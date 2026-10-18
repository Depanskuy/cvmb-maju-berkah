# Bagian 4 — Lamaran & Dashboard Pencari Kerja

| | |
|---|---|
| **Branch** | `feature/4-applications` |
| **Peran blueprint §13** | **D** (Lamaran dan Dashboard Pencari Kerja) |
| **Jumlah route** | **21 dari 57** (bagian dengan route terbanyak) |

## Ruang lingkup

1. **Profil pencari kerja** — buat, lihat, ubah, hapus profil.
2. **Upload CV** — berkas PDF disimpan di disk `local` (bukan publik); unduhan hanya lewat route terotorisasi.
3. **Lamaran** — kirim lamaran, lihat, ubah (selama `pending`), **tarik** lamaran, unduh CV lamaran.
4. **Pencegahan lamaran ganda** — dicek lewat `job_seeker_id` maupun email pelamar; lamaran yang
   **ditarik** (`withdrawn_at`) tidak menghalangi melamar ulang.
5. **Dashboard & riwayat status** — pantau progres `pending → processing → accepted | rejected`.
6. **Jelajahi lowongan dari portal pelamar** — daftar + detail + tombol lamar.
7. **Feedback / ulasan layanan** — dikirim pelamar (bintang 1–5), ditindaklanjuti admin.

> ⚠️ Pelamar **tidak boleh** mengubah status seleksi — itu wewenang admin (Bagian 1).

## Berkas yang dipegang

| Berkas | Keterangan |
|---|---|
| `app/Http/Controllers/Seeker/DashboardController.php` | Dashboard pelamar |
| `app/Http/Controllers/Seeker/ApplicationController.php` | CRUD lamaran + unduh CV lamaran |
| `app/Http/Controllers/Seeker/ProfileController.php` | Profil & CV pelamar |
| `app/Http/Controllers/Seeker/JobController.php` | Jelajahi lowongan dari portal |
| `app/Http/Controllers/Admin/FeedbackController.php` | Tindak lanjut ulasan layanan |
| `app/Http/Requests/StoreApplicationRequest.php` | Validasi lamaran + CV (`mimes:pdf`, maks 2 MB) |
| `app/Models/JobApplication.php`, `app/Models/JobSeekerProfile.php`, `app/Models/Feedback.php` | Model |
| `resources/views/job-seeker/*` | Dashboard, applications, jobs, profile |
| `resources/views/careers/apply-job.blade.php` | Form lamaran (dipakai bersama Bagian 2) |
| `resources/views/company/feedback/index.blade.php` | Daftar ulasan layanan |
| `resources/views/components/seeker-sidebar.blade.php`, `seeker-topbar.blade.php`, `seeker-feedback-modal.blade.php` | Komponen portal pelamar |
| `resources/views/layouts/job-seeker.blade.php` | Layout portal pelamar |
| `public/css/pages.css`, `auth.css` | Gaya form & portal *(token dari Bagian 2)* |

## Route

| Method | URI | Nama |
|---|---|---|
| GET | `/job-seeker/dashboard` | `job-seeker.dashboard` |
| GET | `/job-seeker/jobs` | `job-seeker.jobs.index` |
| GET | `/job-seeker/jobs/{job}` | `job-seeker.jobs.show` |
| GET | `/job-seeker/jobs/{job}/apply` | `job-seeker.jobs.apply` |
| GET | `/job-seeker/applications` | `job-seeker.applications.index` |
| GET | `/job-seeker/applications/{jobApplication}` | `job-seeker.applications.show` |
| GET | `/job-seeker/applications/{jobApplication}/edit` | `job-seeker.applications.edit` |
| PATCH | `/job-seeker/applications/{jobApplication}` | `job-seeker.applications.update` |
| DELETE | `/job-seeker/applications/{jobApplication}` | `job-seeker.applications.destroy` |
| GET | `/job-seeker/applications/{jobApplication}/cv` | `job-seeker.applications.cv` |
| GET | `/job-seeker/profile` | `job-seeker.profile.show` |
| GET | `/job-seeker/profile/create` | `job-seeker.profile.create` |
| POST | `/job-seeker/profile` | `job-seeker.profile.store` |
| GET | `/job-seeker/profile/edit` | `job-seeker.profile.edit` |
| PUT | `/job-seeker/profile` | `job-seeker.profile.update` |
| DELETE | `/job-seeker/profile` | `job-seeker.profile.destroy` |
| GET | `/job-seeker/profile/cv` | `job-seeker.profile.cv` |
| POST | `/job-seeker/feedback` | `job-seeker.feedback.store` |
| GET | `/company/feedback` | `company.feedback.index` |
| PATCH | `/company/feedback/{feedback}/status` | `company.feedback.status.update` |
| DELETE | `/company/feedback/{feedback}` | `company.feedback.destroy` |

## Test terkait

- `tests/Feature/CareerApplicationTest.php` — alur lamaran, lamaran ganda, IDOR, CRUD profil
- `tests/Feature/SecurityAndAccessTest.php` — aturan ubah/tarik lamaran
- `tests/Feature/BusinessModulesTest.php` — modul feedback
- `tests/Feature/FullRouteSmokeTest.php` — seluruh route pelamar

## Checklist tugas

- [ ] Pelamar dapat membuat/melihat/mengubah/menghapus profil
- [ ] CV non-PDF atau > 2 MB ditolak
- [ ] Pelamar dapat melamar lowongan aktif; lamaran ganda dicegah
- [ ] Pelamar dapat **mengubah** lamaran hanya saat `pending`
- [ ] Pelamar dapat **menarik** lamaran hanya saat `pending`; setelah ditarik bisa melamar ulang
- [ ] Pelamar **tidak** dapat mengubah status seleksi
- [ ] Pelamar **tidak** dapat mengakses lamaran pengguna lain (403)
- [ ] Perubahan status oleh admin terlihat di dashboard pelamar
- [ ] Ulasan layanan (1–5) dapat dikirim & ditindaklanjuti admin
- [ ] CV hanya dapat diunduh lewat route terotorisasi

## Koordinasi

- Form lamaran `careers/apply-job.blade.php` dan `Public/CareerController::apply/store` **berbagi**
  berkas dengan Bagian 2 — beri tahu saat mengubah.
- Validasi kolom `applications` dikunci bersama Bagian 1 (kontrak schema).
