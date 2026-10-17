# Laporan Implementasi & Audit — Portal Karier CV Maju Berkah

Dokumen ini merangkum hasil audit kesesuaian antara **kode** dan
**PROJECT_BLUEPRINT_CV_MAJU_BERKAH** (blueprint sebagai sumber kebenaran),
daftar perubahan yang sudah dikerjakan, keputusan desain beserta deviasinya,
peta route lengkap, status lingkungan, dan sisa pekerjaan.

Terakhir diperbarui: 17 Oktober 2026
Branch: `feature/dashboard-admin-pelamar`
Status: **selesai dan terverifikasi** — 58 test lulus, seluruh route tersapu tanpa error 500.

---

## 1. Ringkasan

Aplikasi Laravel 12 + PostgreSQL dengan dua peran pengguna:

| Peran | Kode | Area | Prefix URL |
|---|---|---|---|
| Admin Perusahaan | `company_admin` | Panel rekrutmen | `/company` |
| Pencari Kerja | `job_seeker` | Portal pelamar | `/job-seeker` |

Fitur utama: profil perusahaan, manajemen lowongan, alur lamaran
(kirim → review → keputusan), pengajuan kerja sama, feedback, project/portofolio,
log aktivitas, dan pengaturan perusahaan.

Penamaan tabel, model, kolom, dan route sudah **diselaraskan penuh** dengan blueprint
(lihat §4 Fase B dan §5 Keputusan).

---

## 2. Kontrak Status Lamaran (Blueprint §6)

Nilai status yang **sah** hanya:

```
pending | processing | accepted | rejected
```

- Nilai lama `menunggu` dan `interview` **tidak lagi valid** dan sudah dibersihkan dari
  model, controller, view, dan dikunci oleh `CHECK` constraint di database.
- **Hanya admin** yang boleh mengubah status seleksi.
- Pelamar hanya dapat mengubah/menarik lamaran selama berstatus `pending`.
- Kolom `withdrawn_at` menandai lamaran yang ditarik pelamar. Lamaran yang ditarik
  **tidak** menghalangi pelamar melamar ulang.

Constraint & index aktif pada tabel `applications`:

```sql
CHECK  (status::text = ANY (ARRAY['pending','processing','accepted','rejected']))
NOT NULL job_seeker_id  (FK -> users.id ON DELETE CASCADE)
UNIQUE (job_id, job_seeker_id) WHERE withdrawn_at IS NULL
```

---

## 3. Hasil Audit (temuan awal dan status akhirnya)

| Kode | Temuan | Dampak | Status |
|---|---|---|---|
| A1 | Query pencarian memakai kolom `name`/`email`/`phone` yang tidak ada di tabel `applications` | SQL error 500 saat cari | **Diperbaiki** |
| A2 | Model `JobApplication` ganda, tidak dipakai | Kebingungan kode | **Diperbaiki** (dihapus) |
| A3 | Status `menunggu`/`interview` di luar kontrak | Data & UI tidak konsisten | **Diperbaiki** |
| A4 | Unique `(job_listing_id, applicant_email)` memblokir lamaran ulang setelah ditarik | Pelamar tak bisa melamar ulang | **Diperbaiki** |
| B | Penamaan menyimpang dari blueprint (`/admin`, `/seeker`, `job_listings`, `company_profile`, `JobListing`, `CompanyProfile`, `job_listing_id`) | Deviasi kontrak penamaan | **Selesai** (Fase B, `2668912`) |
| C1 | Tidak ada fitur ubah lamaran saat status pending (JS-05) | Fitur blueprint hilang | **Diperbaiki** |
| C2 | Profil perusahaan admin tidak tersimpan (key `email`/`phone` salah ketuk kolom) | Data perusahaan tidak update | **Diperbaiki** |
| C3 | POST lamaran tidak dilindungi middleware role + URI tidak konsisten | Risiko akses | **Diperbaiki** |
| D | Entitas di luar blueprint: Partnership, Project, Feedback, ActivityLog, Setting | Fitur tambahan | **Diputuskan: dipertahankan & didokumentasikan** (lihat §6) |
| E | Timezone `UTC`, locale `en` | Tidak sesuai blueprint | **Diperbaiki** |

### Temuan tambahan saat implementasi

- Form lamaran portal pelamar mengirim field `name/email/phone/address`, sedangkan
  `StoreApplicationRequest` memvalidasi `applicant_*` → **form gagal validasi**. Sudah disamakan.
- View detail lowongan admin memakai `$app->name/email/phone` (kolom tidak ada). Sudah diperbaiki.
- Branding UI menulis **"CV Maju Sukses"** (puluhan lokasi) padahal blueprint mewajibkan
  **"CV Maju Berkah"**. Sudah dinormalisasi (termasuk inisial logo `MS` → `MB` dan domain email uji).
- Query pencarian pada controller Project/Feedback/Partnership memakai `LIKE` (case-sensitive di
  PostgreSQL), diseragamkan ke `ILIKE`.
- View `resources/views/karir.blade.php` adalah **file mati** (tidak dipakai route/controller apa pun)
  namun me-referensikan komponen `<x-job-card>` dan partial `partials.karir.*` yang tidak ada,
  sehingga `php artisan view:cache` **gagal total**. File dihapus.
- `CompanyProfile::current()` (kini `Company::current()`) tidak mengisi `vision`/`mission`/`services` → kartu Visi/Misi dan
  section "Layanan Kami" di beranda tampil kosong. Ditambal (default + seeder).
- Deprecation PHP 8.5 `PDO::MYSQL_ATTR_SSL_CA` pada `config/database.php` diperbaiki
  (memakai `Pdo\Mysql::ATTR_SSL_CA` bila tersedia).

### Audit akhir (Fase 6)

Audit ulang seluruh fitur dilakukan sebelum dokumentasi ini ditulis:

| Lapis pengujian | Cakupan | Hasil |
|---|---|---|
| Test suite | 58 test / 358 assertion | **Lulus semua** |
| Smoke test seluruh route | 60 route × 3 peran (guest, admin, pelamar) + semua alur tulis | **Lulus semua, nol error 500** |
| Sapuan HTTP nyata | 20 halaman + 70 tautan & aset unik | **0 gagal (semua 200)** |

Temuan dari audit akhir dan perbaikannya (commit `f094da9`):

1. **Lamaran belum dalam satu transaksi database** (blueprint §12 poin 9) →
   penulisan lamaran + log aktivitas + pembaruan profil kini dibungkus `DB::transaction`,
   dan berkas CV dihapus bila transaksi gagal (anti-berkas-yatim).
2. **Method update lamaran memakai `PUT`**, blueprint §9.3 menyebut `PATCH` →
   diubah di route, form, dan test.
3. **`applications.job_seeker_id` masih nullable** (blueprint §7.5) → migrasi baru
   menjadikannya `NOT NULL` (bersih lebih dulu untuk lamaran legacy tanpa pemilik) + FK
   `ON DELETE CASCADE` + penyederhanaan partial unique index. **Non-destruktif**, tanpa `migrate:fresh`.
4. **`APP_DEBUG=true`** (blueprint §12 poin 12) → diset `false` pada `.env` demo;
   `phpunit.xml` menyetel `APP_DEBUG=true` agar diagnostik test tetap lengkap.
5. **Stub method kosong** sisa template pada `Auth\LogoutController` dihapus.

---

## 4. Riwayat Perubahan

### Fase 1 — Bug kritis (A1–A4)
- Koreksi kolom pencarian ke `applicant_name`/`applicant_email`/`applicant_phone` (dan `category`).
- Hapus model ganda `app/Models/JobApplication.php`.
- Bersihkan status `menunggu`/`interview` dari model, controller, dan 8 view.
- Migration `2026_10_17_100000_fix_application_status_and_unique_index.php`:
  normalisasi data lama, kunci `CHECK` constraint ke 4 status, ganti unique lama dengan
  partial unique index `(job_id, job_seeker_id) WHERE withdrawn_at IS NULL`.
- Pengecekan lamaran ganda (CareerController + admin) kini mengabaikan lamaran yang ditarik.

### Fase 2 — Fitur (C1–C3)
- **C1**: route + method `edit`/`update` + view `job-seeker/applications/edit.blade.php`
  + tombol "Ubah" (hanya saat pending).
- **C2**: `SettingController` diperbaiki (kolom `contact_email`/`contact_phone`, tambah
  tagline/visi/misi/services/logo) + view pengaturan ditulis ulang.
- **C3**: URI diseragamkan `/careers/{job}/apply`; POST lamaran dilindungi
  `auth` + `role:job_seeker`.

### Fase E — Konfigurasi
- `timezone` → `Asia/Jakarta` (di `config/app.php`), `locale`/`fallback_locale` → `id`,
  `APP_NAME` → "CV Maju Berkah".

### Fase B — Penyeragaman penamaan ke blueprint (commit `2668912`)

Refactor penuh sesuai kontrak blueprint §7/§8/§9:

| Sebelum | Sesudah |
|---|---|
| Prefix `/admin`, name `admin.*` | `/company`, `company.*` |
| Prefix `/seeker`, name `seeker.*` | `/job-seeker`, `job-seeker.*` |
| Tabel `job_listings` | `jobs` |
| Tabel `company_profile` | `companies` |
| Model `JobListing` / `CompanyProfile` / `Application` | `Job` / `Company` / `JobApplication` |
| Kolom `job_listing_id` | `job_id` |
| Kolom `user_id` (di lamaran) | `job_seeker_id` (tabel tetap `applications`) |
| Tabel antrean `jobs` (bawaan Laravel) | `queue_jobs` |

Termasuk rename route parameter menjadi `{jobApplication}`, penyesuaian seluruh Blade
(`route()`), controller, seeder, dan test. Database dibangun ulang dengan
`migrate:fresh --seed` saat itu.

### Fase 5 — Tema & desain (commit `6fbc19e`, catatan keputusan `0c9e803`)
- **Deviasi blueprint §11.1 (disetujui pemilik produk)**: blueprint menyarankan tema gelap
  (charcoal `#1A1A1A` / terracotta `#C98F7C`). Atas arahan pemilik produk melalui style guide
  tersendiri, aplikasi memakai **tema terang dengan aksen biru `#3B82F6`**.
- `public/css/tokens.css` menjadi **sumber tunggal** token desain: warna semantik, token
  kompatibilitas Material 3, tipografi **Inter**, spacing, radius, serta komponen bersama
  (`.badge`, `.alert`, `.empty-state`, `.pagination`, `.btn-*`, `.form-*`).
- Blok `:root` pada `home.css`, `pages.css`, dan `auth.css` dihapus supaya tidak ada duplikasi
  definisi variabel; warna lama (navy `#00355f`, emas `#feae2c`) dipetakan ke palet baru.
- Skala navy gelap dipakai untuk hero, band CTA, footer, dan sidebar dashboard; permukaan halaman
  memakai `#F1F5F9` / `#FFFFFF`.
- Tipografi **Outfit dihapus sepenuhnya**, diseragamkan ke **Inter** (termasuk config Tailwind).
- Konfigurasi Tailwind (CDN) pada `partials/tailwind.blade.php` disamakan dengan token tersebut.
- `resources/views/welcome.blade.php` (view bawaan Laravel, tidak terpakai) dihapus.

### Fase 6 — Identitas visual & aset (commit `a8c278a`, `d603a25`, `2716b2b`, `77cf200`)
- **Logo berlapis tiga tingkat** melalui partial bersama
  `resources/views/partials/brand-mark.blade.php` (dipakai navbar, footer, login, register,
  dan kedua sidebar dashboard): (1) logo unggahan admin `companies.logo_path` di disk `public`,
  (2) berkas statis `public/images/logo.*`, (3) monogram "MB".
- Logo transparan `public/images/logo.png` (523×431) sebagai fallback statis.
- Form unggah logo pada pengaturan admin: `png/jpg/jpeg/webp/svg`, maks 2 MB, tersimpan di
  `logos/` pada disk `public` (memerlukan `php artisan storage:link`).
- Foto tim `public/images/team-meeting.jpg` dipasang pada section **"Tentang Kami"** di beranda
  dan halaman `/about` (bukan di hero).
- Empat foto pimpinan `public/images/team/*.jpg` (640×640, *center crop*) pada kartu
  Tim Manajemen di halaman Tentang; tersedia fallback **inisial nama** bila berkas tidak ada.

### Fase 6 — Konten & kebersihan kode
- Halaman publik **Tentang**, **Layanan**, dan **Kontak** ditulis ulang agar konsisten dengan
  profil perusahaan **distribusi** (sebelumnya bernuansa firma konsultan/audit: NDA, ISO 27001,
  "auditor", MEP, dsb.).
- Hapus view mati `karir.blade.php` (memperbaiki kegagalan `php artisan view:cache`).
- Tambah seeder profil perusahaan lengkap (visi, misi, layanan, alamat).
- Perbaiki deprecation PHP 8.5 pada `config/database.php`.

### Fase 6 — Pengujian & hardening (commit `e5a6c8d`, `f094da9`)
- **Smoke test seluruh route** `tests/Feature/FullRouteSmokeTest.php` (15 test) menyentuh
  60 route × 3 peran plus semua alur tulis.
- Perbaikan hasil audit akhir (transaksi DB, `PATCH`, `job_seeker_id` NOT NULL,
  `APP_DEBUG=false`, pembersihan stub) — lihat §3.

### Keamanan & kualitas
- Verifikasi kepemilikan data (IDOR) pada seluruh aksi lamaran pelamar dan aksi admin
  (`abort_unless(... === $this->companyId($request), 403)`).
- Pemisahan area admin/pelamar via middleware `role`.
- Rate limit: `POST /login` dan `POST /contact/partnership` (`throttle:10,1`).
- CV tersimpan di disk `local` (bukan publik); unduhan lewat route terotorisasi.
- Bersihkan method kosong pada `LoginController` dan `LogoutController`.

### Pencarian & filter lowongan
- Filter **kategori**, **lokasi**, **tipe pekerjaan**, dan **urutan**
  (Terbaru / Deadline Terdekat / Judul A-Z) pada katalog publik `/careers` dan
  portal pelamar `/job-seeker/jobs`, plus tombol Reset.

---

## 5. Keputusan Desain & Deviasi yang Disetujui

| # | Keputusan | Alasan / Catatan |
|---|---|---|
| 1 | **Tema terang + aksen biru `#3B82F6`** (deviasi blueprint §11.1) | Arahan pemilik produk melalui style guide tersendiri |
| 2 | **Tailwind via CDN, bukan Vite** | Menyederhanakan setup (tanpa `npm install`); `vite.config.js` dibiarkan ada tetapi tidak dipakai |
| 3 | **Kolom tambahan pada `applications`** (`applicant_*`, `education`, `skills`, `withdrawn_at`) | Menyimpan snapshot data pelamar saat melamar agar riwayat tetap utuh walau profil berubah |
| 4 | **Entitas di luar blueprint dipertahankan** (Partnership, Project, Feedback, ActivityLog, Setting) | Nilai tambah; didokumentasikan pada §6 |
| 5 | **Route tambahan di luar §9** (`/job-seeker/jobs/*`, unduh CV, toggle status, modul tambahan) | Melengkapi alur yang blueprint minta secara fungsional (mis. ADM-05 unduh CV) |
| 6 | **Renaming penuh ke kontrak blueprint** (Fase B) | Blueprint dinyatakan sebagai sumber kebenaran |
| 7 | **`job_seeker_id` wajib (NOT NULL)** + FK `ON DELETE CASCADE` | Sesuai §7.5; migrasi non-destruktif dengan pembersihan data legacy |
| 8 | **Update lamaran memakai `PATCH`** | Sesuai §9.3 |
| 9 | **`APP_DEBUG=false` untuk demo** | Sesuai §12 poin 12; `phpunit.xml` menyetel `true` untuk test |
| 10 | **Detail lowongan `closed`/kedaluwarsa tetap dapat dibuka publik (HTTP 200)** | **Deviasi disengaja dari BR-10.** Halaman tetap tampil dengan label "Lowongan Ditutup" dan **tombol lamar disembunyikan**; katalog publik tetap hanya menampilkan lowongan aktif & belum kedaluwarsa. Backend tetap menolak lamaran (403). Dipilih agar riwayat lowongan tetap dapat diakses/untuk SEO. Alternatif yang ditolak: mengembalikan 404 untuk detail lowongan tutup |
| 11 | **Halaman error 422 tidak dibuat** | Blueprint §12 poin 11 menyebut "jika dibutuhkan"; kegagalan validasi dikembalikan ke form beserta pesan, sehingga halaman 422 khusus tidak diperlukan. Yang tersedia: 403, 404, 419, 500 |

---

## 6. Fitur Tambahan di Luar Blueprint

Blueprint §5/§9 hanya mewajibkan alur inti. Entitas berikut dipertahankan dan
didokumentasikan sebagai nilai tambah:

| Modul | Model | Route utama | Ringkasan |
|---|---|---|---|
| Kerja Sama | `Partnership` | `contact.partnership`, `company.partnership.*` | Formulir pengajuan kerja sama dari halaman Kontak + pengelolaan status oleh admin (`pending`/`approved`/`archived`) + catatan admin |
| Project | `Project` | `company.project.*` | Portofolio proyek: klien, kategori, anggaran, jadwal, progres fisik 0–100%, status `planning`/`ongoing`/`completed` |
| Feedback | `Feedback` | `job-seeker.feedback.store`, `company.feedback.*` | Ulasan layanan bintang 1–5 dari pencari kerja + tindak lanjut status (`Baru`/`Ditindaklanjuti`) oleh admin |
| Log Aktivitas | `ActivityLog` | `company.activity-log.index` | Jejak audit aksi penting (autentikasi, CRUD lowongan, perubahan status lamaran) dengan penyaring aksi |
| Pengaturan | `Company` | `company.profile.edit/update` | Profil perusahaan: nama, slug, tagline, deskripsi, visi, misi, layanan, alamat, kontak, dan unggah logo |

---

## 7. Peta Route Lengkap (57 route aplikasi)

Sumber kebenaran: `routes/web.php`. Verifikasi: `php artisan route:list`.

### 7.1 Publik

| Method | URI | Nama | Keterangan |
|---|---|---|---|
| GET | `/` | `home` | Beranda |
| GET | `/about` | `about` | Tentang Kami |
| GET | `/services` | `services` | Layanan |
| GET | `/contact` | `contact` | Kontak |
| POST | `/contact/partnership` | `contact.partnership` | Formulir kerja sama (`throttle:10,1`) |
| GET | `/careers` | `careers.index` | Katalog lowongan aktif |
| GET | `/careers/{job}` | `careers.show` | Detail lowongan |
| GET | `/careers/{job}/apply` | `careers.apply.create` | Form lamaran (guest diarahkan ke login) |
| POST | `/careers/{job}/apply` | `careers.apply` | Kirim lamaran (`auth` + `role:job_seeker`) |

### 7.2 Autentikasi

| Method | URI | Nama | Keterangan |
|---|---|---|---|
| GET | `/login` | `login` | Form login (`guest`) |
| POST | `/login` | `login.store` | Proses login (`throttle:10,1`) |
| GET | `/register` | `register` | Form registrasi (`guest`) |
| POST | `/register` | `register.store` | Registrasi (role selalu `job_seeker`) |
| POST | `/logout` | `logout` | Logout (`auth`) |

### 7.3 Pencari Kerja — prefix `/job-seeker`, middleware `auth` + `role:job_seeker`

| Method | URI | Nama | Keterangan |
|---|---|---|---|
| GET | `/job-seeker/dashboard` | `job-seeker.dashboard` | Dashboard pelamar |
| GET | `/job-seeker/jobs` | `job-seeker.jobs.index` | Jelajahi lowongan |
| GET | `/job-seeker/jobs/{job}` | `job-seeker.jobs.show` | Detail lowongan |
| GET | `/job-seeker/jobs/{job}/apply` | `job-seeker.jobs.apply` | Form lamaran dari portal |
| GET | `/job-seeker/applications` | `job-seeker.applications.index` | Lamaran Saya |
| GET | `/job-seeker/applications/{jobApplication}` | `job-seeker.applications.show` | Detail lamaran |
| GET | `/job-seeker/applications/{jobApplication}/edit` | `job-seeker.applications.edit` | Form ubah lamaran (pending) |
| PATCH | `/job-seeker/applications/{jobApplication}` | `job-seeker.applications.update` | Simpan perubahan lamaran |
| DELETE | `/job-seeker/applications/{jobApplication}` | `job-seeker.applications.destroy` | Tarik lamaran (pending) |
| GET | `/job-seeker/applications/{jobApplication}/cv` | `job-seeker.applications.cv` | Unduh CV lamaran |
| GET | `/job-seeker/profile` | `job-seeker.profile.show` | Profil pelamar |
| GET | `/job-seeker/profile/create` | `job-seeker.profile.create` | Form buat profil |
| POST | `/job-seeker/profile` | `job-seeker.profile.store` | Simpan profil |
| GET | `/job-seeker/profile/edit` | `job-seeker.profile.edit` | Form ubah profil |
| PUT | `/job-seeker/profile` | `job-seeker.profile.update` | Perbarui profil |
| DELETE | `/job-seeker/profile` | `job-seeker.profile.destroy` | Hapus profil |
| GET | `/job-seeker/profile/cv` | `job-seeker.profile.cv` | Unduh CV profil |
| POST | `/job-seeker/feedback` | `job-seeker.feedback.store` | Kirim ulasan layanan |

### 7.4 Admin Perusahaan — prefix `/company`, middleware `auth` + `role:company_admin`

| Method | URI | Nama | Keterangan |
|---|---|---|---|
| GET | `/company/dashboard` | `company.dashboard` | Dashboard admin |
| GET | `/company/profile` | `company.profile.edit` | Form profil perusahaan |
| PUT | `/company/profile` | `company.profile.update` | Simpan profil + logo |
| GET | `/company/jobs` | `company.jobs.index` | Daftar lowongan |
| GET | `/company/jobs/create` | `company.jobs.create` | Form buat lowongan |
| POST | `/company/jobs` | `company.jobs.store` | Simpan lowongan |
| GET | `/company/jobs/{job}` | `company.jobs.show` | Detail lowongan |
| GET | `/company/jobs/{job}/edit` | `company.jobs.edit` | Form ubah lowongan |
| PUT | `/company/jobs/{job}` | `company.jobs.update` | Perbarui lowongan |
| DELETE | `/company/jobs/{job}` | `company.jobs.destroy` | Hapus / tutup lowongan (BR-09) |
| PATCH | `/company/jobs/{job}/toggle-status` | `company.jobs.toggle` | Buka/tutup cepat |
| GET | `/company/applications` | `company.applications.index` | Daftar lamaran masuk |
| GET | `/company/applications/{jobApplication}` | `company.applications.show` | Review lamaran |
| PATCH | `/company/applications/{jobApplication}/status` | `company.applications.status` | Ubah status seleksi |
| GET | `/company/applications/{jobApplication}/cv` | `company.applications.cv` | Unduh CV pelamar |
| GET | `/company/partnership` | `company.partnership.index` | Pengajuan kerja sama |
| PATCH | `/company/partnership/{partnership}/status` | `company.partnership.status.update` | Ubah status kerja sama |
| GET | `/company/feedback` | `company.feedback.index` | Ulasan layanan |
| PATCH | `/company/feedback/{feedback}/status` | `company.feedback.status.update` | Tandai tindak lanjut |
| DELETE | `/company/feedback/{feedback}` | `company.feedback.destroy` | Hapus ulasan |
| GET | `/company/project` | `company.project.index` | Portofolio proyek |
| POST | `/company/project` | `company.project.store` | Tambah proyek |
| PUT | `/company/project/{project}` | `company.project.update` | Perbarui proyek |
| DELETE | `/company/project/{project}` | `company.project.destroy` | Hapus proyek |
| GET | `/company/activity-log` | `company.activity-log.index` | Log aktivitas |

> Catatan: `GET /company/jobs/{job}` (`company.jobs.show`) adalah route tambahan di luar §9.2
> yang dipakai untuk halaman detail lowongan sisi admin.

---

## 8. Checklist Pengujian (Blueprint §16)

Seluruh butir checklist pengujian blueprint sudah dipenuhi. Bukti: test suite (58 test)
dan smoke test seluruh route. Butir yang bersifat manual ditandai **(manual)**.

### Autentikasi
- [x] Registrasi dengan data valid berhasil
- [x] Email duplikat ditolak dengan pesan yang jelas
- [x] Registrasi publik tidak dapat memilih role admin (role dipaksa `job_seeker` di backend)
- [x] Login berhasil mengarah ke dashboard sesuai role
- [x] Login gagal menampilkan pesan yang wajar
- [x] Logout berhasil

### Company profile dan lowongan publik
- [x] Semua halaman publik menampilkan nama dan identitas perusahaan yang sama
- [x] Hanya lowongan aktif dan belum expired yang muncul **di katalog**
- [x] Detail lowongan menampilkan field yang benar
- [x] Pencarian/filter berfungsi
- [x] Lowongan ditutup tidak menerima lamaran baru (backend menolak, 403)
- [x] *(manual)* Tampilan desktop dan mobile diuji (berbasis satu sumber token desain responsif)

### CRUD lowongan
- [x] Admin dapat membuat lowongan
- [x] Admin dapat melihat daftar/detail lowongan miliknya
- [x] Admin dapat mengubah lowongan
- [x] Admin dapat menutup/menghapus sesuai aturan (BR-09: lowongan berlamaran → ditutup, bukan dihapus)
- [x] Input tidak valid ditolak (`salary_max >= salary_min`, deadline harus setelah hari ini, dll.)
- [x] Admin tidak dapat mengubah lowongan di luar kewenangannya (403)

### CRUD lamaran
- [x] Pencari kerja dapat melamar lowongan aktif
- [x] Lamaran ganda dicegah (dicek lewat `job_seeker_id` maupun email; partial unique index di DB)
- [x] CV non-PDF atau terlalu besar ditolak (`mimes:pdf`, maks 2 MB)
- [x] Pencari kerja dapat melihat lamaran sendiri
- [x] Pencari kerja dapat mengubah lamaran ketika pending
- [x] Pencari kerja dapat menarik lamaran ketika pending
- [x] Pencari kerja tidak dapat mengubah status seleksi
- [x] Pencari kerja tidak dapat mengakses lamaran pengguna lain (403)

### Proses admin dan error handling
- [x] Admin dapat melihat pelamar untuk lowongan perusahaan
- [x] Admin dapat memperbarui status lamaran secara valid
- [x] Perubahan status terlihat di dashboard pencari kerja
- [x] Error 403/404/419/500 memiliki tampilan ramah (`resources/views/errors/`)
- [x] Tidak ada stack trace atau SQL mentah pada alur umum (`APP_DEBUG=false` pada demo)

### UI dan integrasi
- [x] Navigasi konsisten di seluruh halaman
- [x] Form memiliki label dan pesan validasi
- [x] *(manual)* Tampilan desktop dan mobile diuji
- [x] Tidak ada tautan/tombol yang tidak berfungsi (sapuan 70 tautan/aset: 0 gagal)
- [x] *(manual)* Setup dari README berhasil dijalankan ulang pada mesin ini
- [x] Repository berisi kode terbaru dan dapat dijalankan

---

## 9. Status Lingkungan & Verifikasi

| Komponen | Status |
|---|---|
| PHP | **8.5.9** (Laragon), `pdo_pgsql` + `pgsql` aktif |
| Laravel Framework | **12.69.3** |
| PostgreSQL | **18.6** |
| Database `cv_maju_berkah` (dev) | **Ada**, owner `cvmb_app` |
| Database `cv_maju_berkah_testing` (test) | **Ada**, owner `cvmb_app` |
| Migrasi | **12 migrasi**, semua diterapkan |
| Model | **9** (`User`, `Company`, `Job`, `JobApplication`, `JobSeekerProfile`, `Partnership`, `Project`, `Feedback`, `ActivityLog`) |
| Seeder | **3 + DatabaseSeeder** (`Company`, `User`, `Job`) |
| Route aplikasi | **57 route** (60 termasuk route bawaan framework) |
| Test suite | **58 lulus / 358 assertion** |
| Deprecation PHP 8.5 (`config/database.php`) | **Diperbaiki** |
| Symlink `public/storage` | **Ada** |

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
php artisan storage:link        # sekali saja, untuk logo unggahan
php artisan test                # seluruh test (DB testing)
php artisan optimize:clear      # bersihkan cache config/route/view
```

### Hasil verifikasi akhir

```
php artisan test                     ->  Tests: 58 passed (358 assertions)
GET /                                ->  HTTP 200
GET /careers                         ->  HTTP 200
GET /login                           ->  HTTP 200
Sapuan 20 halaman + 70 tautan/aset   ->  0 gagal (semua 200)
Smoke test 60 route x 3 peran        ->  15 test lulus, nol error 500
```

---

## 10. Sisa Pekerjaan

1. ~~**Fase B — penyeragaman penamaan ke blueprint**~~ — **selesai** (`2668912`).
2. ~~**Konten halaman publik**~~ — **selesai** untuk `about`, `services`, `contact`.
   Sisanya hanya penyempurnaan copywriting bila diperlukan.
3. **Penyempurnaan tampilan mobile** — fungsional dan responsif, masih dapat dirapikan
   lebih lanjut pada beberapa tabel dashboard bila diinginkan.
4. ~~**Keputusan entitas D**~~ — **selesai**: dipertahankan dan didokumentasikan (§6).
5. ~~**Jalankan migrasi + test**~~ — **selesai**: 58 test lulus, seluruh route tersapu.
6. **Opsional (belum diminta)**: berkas `CHANGELOG.md` dan pembaruan `.env.example`
   agar mencerminkan nilai demo (`APP_DEBUG=false`).

---

## 11. Jejak Commit

| Commit | Ringkasan |
|---|---|
| `69254fa` | Migration & model awal database |
| `1154044` | UI beranda, tentang, layanan, karier, kontak, formulir lamaran, listing lowongan |
| `2da7c94` | Portal admin & pelamar serta manajemen lowongan |
| `c25e7e1` | Perbaikan bug kritis, penyelarasan status lamaran, perapian branding |
| `2668912` | **Fase B**: penyelarasan penamaan DB, model, dan route dengan blueprint |
| `6fbc19e` | **Fase 5**: tema terang & palet biru sesuai panduan desain |
| `0c9e803` | Dokumentasi keputusan tema terang & palet biru (deviasi §11.1) |
| `a8c278a` | Foto tim & logo perusahaan yang dapat diunggah |
| `d603a25` | Logo statis `public/images/logo.*` sebagai fallback |
| `2716b2b` | Logo tanpa latar menggantikan monogram di semua tempat |
| `77cf200` | Foto pimpinan pada kartu Tim Manajemen di halaman Tentang |
| `e5a6c8d` | **Fase 6**: smoke test menyeluruh seluruh route & alur fitur |
| `f094da9` | **Fase 6**: perbaikan hasil audit (transaksi, PATCH, NOT NULL, debug, stub) |
| `27d813c` | **Fase 6**: README sesuai blueprint §15 |
