# Kamu ada di branch ini — Bagian 3

> Branch: `feature/3-jobs-crud`

| | |
|---|---|
| Bagian | 3 — CRUD Lowongan Admin |
| Peran blueprint §13 | **C** (CRUD Lowongan Admin) |
| Route | 13 dari 57 |
| Ruang lingkup lengkap | [`../3-jobs-crud.md`](../3-jobs-crud.md) |

## Kamu memegang
- CRUD lowongan + validasi (`salary_max >= salary_min`, deadline masa depan).
- Buka/tutup cepat status lowongan.
- Dashboard ringkasan admin.
- Aturan **BR-09**: lowongan yang sudah berlamaran ditutup, bukan dihapus.
- Modul project/portofolio (CRUD).
- Batas kepemilikan: admin hanya mengelola lowongan perusahaannya (403 bila bukan).

## Langkah mulai
1. `composer install` → salin `.env.example` ke `.env` → `php artisan key:generate`
2. `php artisan migrate --seed`
3. `php artisan serve`, login `admin@majuberkah.test` / `password`

## Ingat
- `app/Models/Job.php` dipakai bersama (Bagian 2 & 4): hindari edit bersamaan.
- Mengubah kolom/status lowongan wajib koordinasi dengan Bagian 1, 2, dan 4.
