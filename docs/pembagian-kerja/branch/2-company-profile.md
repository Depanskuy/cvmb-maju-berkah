# Kamu ada di branch ini — Bagian 2

> Branch: `feature/2-company-profile`

| | |
|---|---|
| Bagian | 2 — Company Profile & UI Publik |
| Peran blueprint §13 | **B** (Company Profile dan UI Publik) |
| Route | 11 dari 57 |
| Ruang lingkup lengkap | [`../2-company-profile.md`](../2-company-profile.md) |

## Kamu memegang
- Halaman publik: beranda, tentang, layanan, kontak.
- Layout & komponen bersama: navbar, footer, `brand-mark` (logo berlapis), Tailwind CDN.
- Design token `public/css/tokens.css` — satu-satunya sumber warna/tipografi/spacing.
- Katalog & detail lowongan publik + pencarian/filter.
- Profil perusahaan sisi admin + unggah logo.
- Modul kerja sama (formulir dari halaman Kontak).
- Aset: logo dan foto tim.

## Langkah mulai
1. `composer install` → salin `.env.example` ke `.env` → `php artisan key:generate`
2. `php artisan migrate --seed` lalu `php artisan storage:link` (wajib untuk logo unggahan)
3. `php artisan serve` lalu buka `http://127.0.0.1:8000`

## Ingat
- Jangan ubah schema `jobs` tanpa koordinasi dengan Bagian 3.
- Perubahan pada `Public/CareerController` bagian katalog → beri tahu Bagian 4.
- Token/warna baru diputuskan di sini; jangan menulis ulang variabel di CSS lain.
