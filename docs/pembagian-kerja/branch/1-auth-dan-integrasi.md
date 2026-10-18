# Kamu ada di branch ini — Bagian 1

> Branch: `feature/1-auth-dan-integrasi`

| | |
|---|---|
| Bagian | 1 — Autentikasi, Integrasi & Dokumentasi |
| Peran blueprint §13 | **A** (Koordinator/Integrasi) + **E** (QA & Dokumentasi) |
| Route | 12 dari 57 |
| Ruang lingkup lengkap | [`../1-auth-dan-integrasi.md`](../1-auth-dan-integrasi.md) |

## Kamu memegang
- Autentikasi: login, register (role dipaksa `job_seeker`), logout, rate limit.
- Struktur database (12 migrasi) sebagai penjaga kontrak schema.
- Konfigurasi: `.env.example`, timezone `Asia/Jakarta`, locale `id`.
- Seeder demo: `UserSeeder`, `CompanySeeder`, `JobSeeder`.
- Daftar pelamar admin + update status lamaran + unduh CV.
- Log aktivitas (audit trail).
- QA: seluruh test suite + smoke test seluruh route.
- Dokumentasi: `README.md`, `docs/LAPORAN_IMPLEMENTASI.md`, `docs/pembagian-kerja/`.
- Review & merge akhir ke branch integrasi.

## Langkah mulai
1. `composer install`
2. Salin `.env.example` menjadi `.env`, isi koneksi PostgreSQL, lalu `php artisan key:generate`
3. `php artisan migrate --seed` lalu `php artisan storage:link`
4. `php artisan serve`, cek `php artisan test` (harus 77 test lulus)

## Ingat
- Kamu penjaga kontrak: perubahan nama tabel/kolom/route harus lewat bagian ini.
- Migration baru dari bagian lain diajukan dulu agar tidak duplikat.
- Jangan commit `.env`, kredensial, atau CV pelamar.
