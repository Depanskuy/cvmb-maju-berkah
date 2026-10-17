# Portal Karier CV Maju Berkah

Aplikasi web profil perusahaan sekaligus portal rekrutmen untuk **CV Maju Berkah**
(perusahaan distribusi barang & penyediaan kebutuhan usaha).

Dibangun dengan **Laravel 12**, **PostgreSQL**, **Tailwind CSS**, antarmuka **Bahasa Indonesia**,
dan zona waktu **Asia/Jakarta**.

---

## Fitur

- **Publik**: beranda, tentang, layanan, kontak + pengajuan kerja sama, katalog karier, detail lowongan.
- **Pencari kerja (`job_seeker`)**: dashboard, jelajahi lowongan (pencarian + filter kategori/lokasi/tipe/urutan),
  kirim lamaran, ubah lamaran saat masih *pending*, tarik lamaran, pantau status, kelola profil & CV, kirim feedback.
- **Admin perusahaan (`company_admin`)**: dashboard, CRUD lowongan, review & update status lamaran,
  pengajuan kerja sama, feedback, project, log aktivitas, pengaturan/profil perusahaan.

## Kontrak Status Lamaran

```
pending → processing → accepted | rejected
```

Lamaran yang ditarik pelamar (`withdrawn_at`) tidak menghalangi lamaran ulang.

---

## Kebutuhan Sistem

- PHP 8.4+ dengan ekstensi **pdo_pgsql**
- Composer
- PostgreSQL 14+
- Node.js (opsional, untuk aset)

> Catatan Windows/Laragon: pastikan `extension=pdo_pgsql` aktif di `php.ini`.
> Jika belum, jalankan perintah artisan dengan `php -d extension=pdo_pgsql artisan ...`.

---

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database di `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cv_maju_berkah
DB_USERNAME=cvmb_app
DB_PASSWORD=cvmb_dev_2026
```

> Untuk produksi, ganti password di atas dan simpan kredensial di luar repositori.

Siapkan database & data contoh:

```bash
php artisan migrate --seed
```

Jalankan server:

```bash
php artisan serve
```

---

## Akun Demo (dari `UserSeeder`)

| Peran | Email | Password |
|---|---|---|
| Admin Perusahaan | `admin@majuberkah.test` | `password` |
| Pencari Kerja | `seeker@majuberkah.test` | `password` |

---

## Pengujian

```bash
php artisan test
```

Berkas test utama:

- `tests/Feature/CareerApplicationTest.php` — alur lamaran publik.
- `tests/Feature/BusinessModulesTest.php` — modul bisnis.
- `tests/Feature/SecurityAndAccessTest.php` — keamanan akses, filter, ubah lamaran, profil perusahaan.

---

## Dokumentasi

Lihat [`docs/LAPORAN_IMPLEMENTASI.md`](docs/LAPORAN_IMPLEMENTASI.md) untuk laporan audit,
perubahan, catatan lingkungan, dan sisa pekerjaan.

## Lisensi

Proprietary — internal CV Maju Berkah.
