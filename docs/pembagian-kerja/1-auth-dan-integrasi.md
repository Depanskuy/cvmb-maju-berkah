# Bagian 1 — Autentikasi, Integrasi & Dokumentasi

| | |
|---|---|
| **Branch** | `feature/1-auth-dan-integrasi` |
| **Peran blueprint §13** | **A** (Koordinator/Integrasi) + **E** (Integrasi Rekrutmen, QA & Dokumentasi) |
| **Jumlah route** | 12 dari 57 |

## Ruang lingkup

1. **Autentikasi** — login, register (role publik selalu dipaksa `job_seeker`), logout,
   rate limit `POST /login`.
2. **Integrasi & koordinasi** — menjaga `routes/web.php`, `bootstrap/app.php`, middleware `role`,
   dan memastikan nama route/model/kolom tetap sesuai kontrak blueprint.
3. **Schema & data inti** — seluruh migrasi (12) sebagai penjaga kontrak, plus seeder demo.
4. **Konfigurasi** — `.env.example`, versi framework, timezone `Asia/Jakarta`, locale `id`.
5. **Daftar pelamar admin + update status lamaran** (bagian Peran E).
6. **Log aktivitas** (audit trail lintas modul).
7. **QA** — menjalankan seluruh test suite + smoke test seluruh route sebelum merge.
8. **Dokumentasi** — `README.md`, `docs/LAPORAN_IMPLEMENTASI.md`, `docs/pembagian-kerja/`.
9. **Review & merge akhir** pekerjaan bagian lain ke branch integrasi.

## Berkas yang dipegang

| Berkas | Keterangan |
|---|---|
| `app/Http/Controllers/Auth/LoginController.php` | Login + rate limit |
| `app/Http/Controllers/Auth/RegisterController.php` | Registrasi (role dipaksa `job_seeker`) |
| `app/Http/Controllers/Auth/LogoutController.php` | Logout |
| `app/Http/Controllers/Admin/ApplicationController.php` | Daftar pelamar, detail, update status, unduh CV |
| `app/Http/Controllers/Admin/ActivityLogController.php` | Log aktivitas |
| `app/Http/Middleware/EnsureUserHasRole.php` | Pemisahan area admin/pelamar |
| `routes/web.php`, `bootstrap/app.php` | Kontrak route & middleware |
| `config/app.php`, `config/database.php` | Timezone, locale, koneksi |
| `database/migrations/*` (12 berkas) | Struktur database |
| `database/seeders/*` | `DatabaseSeeder`, `UserSeeder`, `CompanySeeder`, `JobSeeder` |
| `app/Models/ActivityLog.php`, `app/Models/User.php` | Model pendukung |
| `resources/views/auth/login.blade.php`, `register.blade.php` | Halaman auth |
| `resources/views/company/applications/*` | Review lamaran sisi admin |
| `resources/views/company/activity-log/index.blade.php` | Log aktivitas |
| `resources/views/layouts/guest.blade.php`, `resources/views/errors/*` | Layout & halaman error 403/404/419/500 |
| `tests/*` | Seluruh pengujian (QA) |
| `README.md`, `docs/*` | Dokumentasi |

## Route

| Method | URI | Nama |
|---|---|---|
| GET | `/login` | `login` |
| POST | `/login` | `login.store` |
| GET | `/register` | `register` |
| POST | `/register` | `register.store` |
| POST | `/logout` | `logout` |
| GET | `/careers/{job}/apply` | `careers.apply.create` |
| POST | `/careers/{job}/apply` | `careers.apply` |
| GET | `/company/applications` | `company.applications.index` |
| GET | `/company/applications/{jobApplication}` | `company.applications.show` |
| PATCH | `/company/applications/{jobApplication}/status` | `company.applications.status` |
| GET | `/company/applications/{jobApplication}/cv` | `company.applications.cv` |
| GET | `/company/activity-log` | `company.activity-log.index` |

## Test terkait

- `tests/Feature/SecurityAndAccessTest.php` — akses per peran, kepemilikan data (IDOR)
- `tests/Feature/FullRouteSmokeTest.php` — seluruh route × 3 peran, deteksi error 500
- `tests/Feature/CareerApplicationTest.php` — alur lamaran end-to-end
- `tests/Feature/BusinessModulesTest.php` — modul bisnis

## Checklist tugas

- [ ] Registrasi valid berhasil; email duplikat ditolak dengan pesan jelas
- [ ] Role publik tidak bisa memilih admin (dipaksa `job_seeker` di backend)
- [ ] Login mengarah ke dashboard sesuai peran; login gagal memberi pesan wajar
- [ ] Logout berhasil; rate limit `POST /login` aktif
- [ ] Admin hanya bisa melihat lamaran untuk lowongan perusahaannya (403 bila bukan)
- [ ] `php artisan test` hijau seluruhnya
- [ ] Smoke test seluruh route: nol error 500
- [ ] `README.md` dan `docs/LAPORAN_IMPLEMENTASI.md` masih akurat
- [ ] Tidak ada `.env`/kredensial/CV ter-commit

## Koordinasi

- Bagian 1 adalah **penjaga kontrak**: perubahan nama tabel/kolom/route harus lewat bagian ini.
- Migration baru dari bagian lain diajukan dulu agar tidak duplikat.
