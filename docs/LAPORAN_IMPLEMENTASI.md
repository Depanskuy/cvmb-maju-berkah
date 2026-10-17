# Laporan Implementasi & Audit — Portal Karier CV Maju Berkah

Dokumen ini merangkum hasil audit kesesuaian antara **kode** dan **PROJECT_BLUEPRINT_CV_MAJU_BERKAH**
(blueprint sebagai sumber kebenaran), perubahan yang sudah dikerjakan, temuan lingkungan,
serta sisa pekerjaan.

Terakhir diperbarui: 17 Oktober 2026
Branch: `feature/dashboard-admin-pelamar`

---

## 1. Ringkasan

Aplikasi Laravel 12 + PostgreSQL dengan dua peran pengguna:

| Peran | Kode | Area | Prefix URL |
|---|---|---|---|
| Admin Perusahaan | `company_admin` | Panel rekrutmen | `/admin` |
| Pencari Kerja | `job_seeker` | Portal pelamar | `/seeker` |

Fitur utama: profil perusahaan, lowongan kerja, alur lamaran (kirim → review → keputusan),
pengajuan kerja sama, feedback, log aktivitas, dan pengaturan.

---

## 2. Kontrak Status Lamaran (Blueprint §6)

Nilai status yang **sah** hanya:

```
pending | processing | accepted | rejected
```

Nilai lama `menunggu` dan `interview` **tidak lagi valid** dan sudah dibersihkan dari
model, controller, view, dan constraint database.

Kolom `withdrawn_at` menandai lamaran yang ditarik pelamar. Lamaran yang ditarik
**tidak** menghalangi pelamar melamar ulang.

---

## 3. Hasil Audit (temuan awal)

| Kode | Temuan | Dampak | Status |
|---|---|---|---|
| A1 | Query pencarian memakai kolom `name`/`email`/`phone` yang tidak ada di tabel `applications` | SQL error 500 saat cari | **Diperbaiki** |
| A2 | Model `JobApplication` ganda, tidak dipakai | Kebingungan kode | **Diperbaiki** (dihapus) |
| A3 | Status `menunggu`/`interview` di luar kontrak | Data & UI tidak konsisten | **Diperbaiki** |
| A4 | Unique `(job_listing_id, applicant_email)` memblokir lamaran ulang setelah ditarik | Pelamar tak bisa melamar ulang | **Diperbaiki** |
| B | Penamaan menyimpang dari blueprint (`/seeker` vs `/job-seeker`, tabel `job_listings` vs `jobs`, dsb.) | Deviasi kontrak penamaan | **Ditunda** |
| C1 | Tidak ada fitur ubah lamaran saat status pending (JS-05) | Fitur blueprint hilang | **Diperbaiki** |
| C2 | Profil perusahaan admin tidak tersimpan (key `email`/`phone` salah ketuk kolom) | Data perusahaan tidak update | **Diperbaiki** |
| C3 | POST lamaran tidak dilindungi middleware role + URI tidak konsisten | Risiko akses | **Diperbaiki** |
| D | Entitas di luar blueprint: Partnership, Project, Feedback, ActivityLog, Setting | Fitur tambahan | **Perlu keputusan** |
| E | Timezone `UTC`, locale `en` | Tidak sesuai blueprint | **Diperbaiki** |

### Temuan tambahan saat implementasi

- Form lamaran portal pelamar mengirim field `name/email/phone/address`, sedangkan
  `StoreApplicationRequest` memvalidasi `applicant_*` → **form gagal validasi**. Sudah disamakan.
- View `admin/lowongan/show.blade.php` memakai `$app->name/email/phone` (kolom tidak ada). Sudah diperbaiki.
- Branding UI menulis **"CV Maju Sukses"** (puluhan lokasi) padahal blueprint mewajibkan
  **"CV Maju Berkah"**. Sudah dinormalisasi (termasuk inisial logo `MS` → `MB` dan domain email uji).
- Query pencarian pada controller Project/Feedback/Partnership memakai `LIKE` (case-sensitive di
  PostgreSQL), diseragamkan ke `ILIKE`.
- View `resources/views/karir.blade.php` adalah **file mati** (tidak dipakai route/controller mana pun)
  namun me-referensikan komponen `<x-job-card>` dan partial `partials.karir.*` yang tidak ada,
  sehingga `php artisan view:cache` **gagal total**. File dihapus.
- `CompanyProfile::current()` tidak mengisi `vision`/`mission`/`services` → kartu Visi/Misi dan
  section "Layanan Kami" di beranda tampil kosong. Ditambal (default + `CompanyProfileSeeder`).
- Deprecation PHP 8.5 `PDO::MYSQL_ATTR_SSL_CA` pada `config/database.php` diperbaiki
  (memakai `Pdo\Mysql::ATTR_SSL_CA` bila tersedia).

---

## 4. Perubahan yang Dikerjakan

### Fase 1 — Bug kritis (A1–A4)
- Koreksi kolom pencarian ke `applicant_name`/`applicant_email`/`applicant_phone` (dan `category`).
- Hapus model `app/Models/JobApplication.php`.
- Bersihkan status `menunggu`/`interview` dari model, controller, dan 8 view.
- Migration baru `2026_10_17_100000_fix_application_status_and_unique_index.php`:
  normalisasi data lama, kunci CHECK constraint ke 4 status, ganti unique lama dengan
  partial unique index `(job_listing_id, user_id) WHERE withdrawn_at IS NULL`.
- Pengecekan lamaran ganda (CareerController + JobController) kini mengabaikan lamaran yang ditarik.

### Fase 2 — Fitur (C1–C3)
- **C1**: route + method `edit`/`update` + view `seeker/applications/edit.blade.php`
  + tombol "Ubah" (hanya saat pending).
- **C2**: `SettingController` diperbaiki (kolom `contact_email`/`contact_phone`, tambah
  tagline/visi/misi/services/logo) + view pengaturan ditulis ulang.
- **C3**: URI diseragamkan `/careers/{jobListing}/apply`; POST lamaran dilindungi
  `auth` + `role:job_seeker`.

### Fase E — Konfigurasi
- `timezone` → `Asia/Jakarta`, `locale`/`fallback_locale` → `id`, `APP_NAME` → "CV Maju Berkah".

### Keamanan & kualitas
- Verifikasi kepemilikan data (IDOR) pada seluruh aksi lamaran pelamar.
- Pemisahan area admin/pelamar via middleware `role`.
- Tambah rate-limit: `POST /login` (`throttle:10,1`), `POST /contact/partnership` (`throttle:10,1`).
- Bersihkan method kosong pada `LoginController`.

### Pencarian & filter lowongan
- Tambah filter **kategori**, **lokasi**, dan **urutan** (Terbaru / Deadline Terdekat / Judul A-Z)
  pada katalog publik (`/careers`) dan portal pelamar (`/seeker/jobs`), plus tombol Reset.

### Testing
- `tests/Feature/SecurityAndAccessTest.php` — akses per-peran, IDOR, aturan lamaran ulang,
  ubah lamaran, update status, filter lowongan, profil perusahaan.

### Branding
- Normalisasi teks "Maju Sukses" → "Maju Berkah" dan domain `majusukses` → `majuberkah`
  di view, CSS, seeder, dan test.
- Inisial logo `MS` → `MB` pada halaman login, register, dan beranda.

### Tema & desain (Fase 5)
- **Deviasi blueprint §11.1 (disetujui pemilik produk)**: blueprint menyarankan tema gelap
  (charcoal `#1A1A1A` / terracotta `#C98F7C`). Atas arahan pemilik produk melalui style guide
  tersendiri, aplikasi memakai **tema terang dengan aksen biru `#3B82F6`**.
- `public/css/tokens.css` menjadi **sumber tunggal** token desain: warna semantik, token
  kompatibilitas Material 3, tipografi **Inter** (Heading 1 32px, Heading 2 24px, Heading 3
  20px, body 16px, small 14px, caption 12px), spacing, radius, serta komponen bersama
  (`.badge`, `.alert`, `.empty-state`, `.pagination`, `.btn-*`, `.form-*`).
- Blok `:root` pada `home.css`, `pages.css`, dan `auth.css` dihapus supaya tidak ada duplikasi
  definisi variabel; warna lama (navy `#00355f`, emas `#feae2c`) dipetakan ke palet baru.
- Skala navy gelap (`#0F2E5B` / `#153F91` / `#2563EB`) dipakai untuk hero, band CTA, footer,
  dan sidebar dashboard; permukaan halaman memakai `#F1F5F9` / `#FFFFFF`.
- Konfigurasi Tailwind (CDN) pada `partials/tailwind.blade.php` disamakan dengan token tersebut
  agar dashboard dan halaman publik konsisten.
- `resources/views/welcome.blade.php` (view bawaan Laravel, tidak terpakai) dihapus.

### Konten & kebersihan kode
- Halaman publik **Tentang** (`about`), **Layanan** (`services`), dan **Kontak** (`contact`)
  ditulis ulang agar konsisten dengan profil perusahaan **distribusi** (sebelumnya bernuansa
  firma konsultan/audit: NDA, ISO 27001, "auditor", MEP, dsb.).
- Hapus view mati `karir.blade.php` (memperbaiki kegagalan `php artisan view:cache`).
- Tambah `CompanyProfileSeeder` + default profil perusahaan lengkap (visi, misi, layanan, alamat).
- Perbaiki deprecation PHP 8.5 pada `config/database.php`.

---

## 5. Status Lingkungan (SUDAH BERES)

| Komponen | Status |
|---|---|
| Ekstensi `pdo_pgsql` + `pgsql` (Laragon PHP 8.5.9) | **Aktif** di `php.ini` |
| Database `cv_maju_berkah` (dev) | **Ada**, owner `cvmb_app` |
| Database `cv_maju_berkah_testing` (test) | **Ada**, owner `cvmb_app` |
| Migrasi | **Diterapkan** (12 migrasi, termasuk perbaikan status & partial unique index) |
| Seeder | **Dijalankan** (`CompanyProfile`, `JobListing`, `User`) |
| Test suite | **43 lulus / 194 assertion** |
| Deprecation PHP 8.5 (`config/database.php`) | **Diperbaiki** |

### Kredensial aplikasi (lihat `.env`)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cv_maju_berkah
DB_USERNAME=cvmb_app
DB_PASSWORD=cvmb_dev_2026
```

> **Catatan:** koneksi sebelumnya memakai superuser `postgres`, tetapi passwordnya tidak
> diketahui dan PostgreSQL 18 memakai `scram-sha-256`. Solusinya dibuat **user aplikasi
> khusus `cvmb_app`** (password superuser **tidak** diubah) dan autentikasi dikembalikan
> ke `scram-sha-256`. Untuk produksi: ganti password ini dan simpan di luar repositori.

### Perintah rutin

```bash
php artisan migrate --seed      # skema + data contoh (DB dev)
php artisan test                # seluruh test (DB testing)
php artisan optimize:clear      # bersihkan cache config/route/view
```

Menyiapkan ulang DB testing bila perlu:

```powershell
$env:DB_DATABASE='cv_maju_berkah_testing'; php artisan migrate --seed --force; Remove-Item Env:DB_DATABASE
```

### Hasil verifikasi

```
php artisan test  ->  Tests: 43 passed (194 assertions)
GET /             ->  HTTP 200
GET /careers      ->  HTTP 200
GET /login        ->  HTTP 200
```

Constraint & index yang aktif pada tabel `applications`:

```
CHECK  (status::text = ANY (ARRAY['pending','processing','accepted','rejected']))
UNIQUE (job_listing_id, user_id) WHERE withdrawn_at IS NULL AND user_id IS NOT NULL
```

---

## 6. Sisa Pekerjaan (belum dikerjakan)

1. **Fase B — penyeragaman penamaan ke blueprint** (refactor besar):
   - `/admin`+`admin.` → `/company`+`company.`, `/seeker`+`seeker.` → `/job-seeker`+`job-seeker.`
   - tabel `job_listings`→`jobs`, `company_profile`→`companies`
   - model `JobListing`→`Job`, `CompanyProfile`→`Company`
   - kolom `job_listing_id`→`job_id`, `user_id`→`job_seeker_id`
   Berisiko tinggi karena menyentuh hampir semua Blade (`route()`), controller, dan migration.
2. ~~**Konten halaman publik**~~ — **sudah dikerjakan** untuk `about`, `services`, `contact`
   (kini bernuansa distribusi). Sisanya hanya penyempurnaan copywriting bila diperlukan.
3. **Tampilan mobile & keseragaman UI/UX** antar halaman.
4. **Keputusan entitas D**: pertahankan (dokumentasikan sebagai tambahan) atau hapus.
5. ~~**Jalankan migrasi + test**~~ — **selesai**: 43 test lulus, halaman utama HTTP 200.

---

## 7. Peta Route Utama

| Method | URI | Nama | Keterangan |
|---|---|---|---|
| GET | `/careers` | `careers.index` | Katalog publik |
| GET | `/careers/{jobListing}` | `careers.show` | Detail publik |
| GET | `/careers/{jobListing}/apply` | `careers.apply.create` | Form lamaran |
| POST | `/careers/{jobListing}/apply` | `careers.apply.store` | Kirim lamaran (auth + job_seeker) |
| GET | `/seeker/applications` | `seeker.applications` | Lamaran saya |
| GET | `/seeker/applications/{application}/edit` | `seeker.applications.edit` | Ubah lamaran |
| PUT | `/seeker/applications/{application}` | `seeker.applications.update` | Simpan perubahan |
| DELETE | `/seeker/applications/{application}/withdraw` | `seeker.applications.withdraw` | Tarik lamaran |
| GET | `/admin/lamaran/{application}` | `admin.lamaran.show` | Review lamaran |
| PATCH | `/admin/lamaran/{application}/status` | `admin.lamaran.status.update` | Update status |
| PUT | `/admin/pengaturan` | `admin.pengaturan.update` | Profil perusahaan |
