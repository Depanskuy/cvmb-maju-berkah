# Kamu ada di branch ini — Bagian 4

> Branch: `feature/4-applications`

| | |
|---|---|
| Bagian | 4 — Lamaran & Dashboard Pencari Kerja |
| Peran blueprint §13 | **D** (Lamaran dan Dashboard Pencari Kerja) |
| Route | 21 dari 57 (terbanyak) |
| Ruang lingkup lengkap | [`../4-applications.md`](../4-applications.md) |

## Kamu memegang
- Profil pencari kerja (buat/lihat/ubah/hapus) + upload CV (PDF, disk `local`).
- Lamaran: kirim, lihat, ubah (saat `pending`), tarik, unduh CV.
- Pencegahan lamaran ganda (`job_seeker_id`/email; lamaran yang ditarik boleh melamar ulang).
- Dashboard & riwayat status seleksi pelamar.
- Jelajahi lowongan dari portal pelamar.
- Modul feedback/ulasan layanan (bintang 1–5) + tindak lanjut admin.

## Langkah mulai
1. `composer install` → salin `.env.example` ke `.env` → `php artisan key:generate`
2. `php artisan migrate --seed` lalu `php artisan storage:link`
3. `php artisan serve`, login `seeker@majuberkah.test` / `password`

## Ingat
- Pelamar **tidak boleh** mengubah status seleksi — itu wewenang admin (Bagian 1).
- Form lamaran & `Public/CareerController::apply/store` dibagi dengan Bagian 2.
- Validasi kolom `applications` dikunci bersama Bagian 1.
