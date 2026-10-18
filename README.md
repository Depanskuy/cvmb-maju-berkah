# DOKUMENTASI SELURUH PROJECT
## Portal Karier CV Maju Berkah — Company Profile & Career Management System

Berkas ini menjelaskan **seluruh project dari awal sampai kondisi akhir** dalam satu tempat:
apa yang dibangun, untuk siapa, bagaimana cara kerjanya, apa saja yang sudah diuji, keputusan
apa yang diambil beserta alasannya, dan bagaimana riwayat pengerjaannya.

Disusun: 17 Oktober 2026 · Sumber informasi: `PROJECT_BLUEPRINT_CV_MAJU_BERKAH.md`,
`README.md`, `docs/LAPORAN_IMPLEMENTASI.md`, `CHANGELOG.md`, dan riwayat Git project.

Berkas ini bersifat **penjelasan/rangkuman**. Sumber kebenaran yang mengikat tetap
`PROJECT_BLUEPRINT_CV_MAJU_BERKAH.md`; detail teknis terdalam ada di
`docs/LAPORAN_IMPLEMENTASI.md`; daftar commit ada di `RIWAYAT_PERUBAHAN.txt`.

### Daftar isi
1. Ringkasan project
2. Identitas perusahaan & brand
3. Teknologi & versi
4. Cara menjalankan
5. Struktur direktori
6. Peran & hak akses
7. Basis data
8. Peta route (57 route)
9. Fitur & penjelasan
10. Kontrak status (dan alasan di baliknya)
11. Aturan bisnis yang diterapkan
12. Keamanan & perlindungan data
13. Pengujian
14. Keputusan desain & deviasi
15. Pembagian kerja 4 bagian
16. Riwayat perubahan
17. Definition of Done, sisa pekerjaan & berkas penting

---

## 1. Ringkasan Project

**Apa:** sebuah **portal karier + company profile** berbasis web untuk perusahaan fiktif
**CV Maju Berkah** (perusahaan distribusi barang). Portal ini menggabungkan dua hal:

1. **Sisi publik** — memperkenalkan perusahaan (beranda, tentang, layanan, kontak) sekaligus
   membuka **katalog lowongan** yang bisa dilamar siapa saja.
2. **Sisi internal** — dua dashboard yang dipisahkan tegas: **Admin Perusahaan** mengelola
   lowongan & lamaran, **Pencari Kerja** mengelola profil dan lamarannya.

**Masalah yang diselesaikan:** sebelumnya lowongan dan lamaran tidak punya satu tempat yang
rapi. Sekarang proses rekrutmen end-to-end berjalan di satu aplikasi: admin membuka lowongan →
pelamar melamar lewat web → admin menyeleksi lewat dashboard → keputusan seleksi langsung
terlihat di dashboard pelamar.

**Dua peran pengguna:**

| Peran | Kode teknis | Inti pekerjaan di aplikasi |
|---|---|---|
| Admin Perusahaan | `company_admin` | Kelola lowongan, tinjau lamaran, putuskan seleksi, kelola profil perusahaan + modul kerja sama/project/feedback |
| Pencari Kerja | `job_seeker` | Lengkapi profil & CV, cari lowongan, melamar, pantau status lamaran |

**Ukuran project (kondisi akhir):**

| Ukuran | Nilai |
|---|---|
| Route aplikasi | **57** (total 60 termasuk 3 route bawaan framework) |
| Tabel database | 16 (8 tabel aplikasi + 8 tabel bawaan Laravel) |
| Migrasi | 12 berkas migrasi |
| Model Eloquent | 9 model |
| Seeder | 4 (1 penggabung + 3 seeder data) |
| Berkas view (blade) | 54 berkas dalam 9 folder |
| Berkas kode PHP aplikasi | 111 berkas, ±12.856 baris |
| Test otomatis | **77 test / 413 assertion — semuanya lulus** |
| Halaman error kustom | 403, 404, 419, 500 |
| Commit | 20 commit + 4 commit penanda branch pembagian kerja |
| Status | Selesai, teruji, dan terdokumentasi; **belum di-push** ke GitHub |

---

## 2. Identitas Perusahaan & Brand

Dipilih sebagai perusahaan **distribusi barang** (bukan konsultan), dan seluruh isi halaman
publik ditulis ulang agar konsisten dengan profil tersebut.

| Aspek | Nilai |
|---|---|
| Nama | CV Maju Berkah |
| Bidang | Distribusi barang |
| Bahasa antarmuka | **Bahasa Indonesia** |
| Zona waktu aplikasi | **Asia/Jakarta** |
| Tema | **Terang (light)** dengan aksen **biru `#3B82F6`** — sesuai style guide pemilik produk (ini deviasi dari blueprint §11.1, lihat bab 14) |
| Tipografi | **Inter** (font Outfit dihapus sepenuhnya agar seragam) |
| Logo | Berlapis tiga: (1) logo unggahan admin, (2) berkas statis `public/images/logo.*`, (3) monogram "MB" |
| Foto | Foto tim pada section "Tentang Kami" + 4 foto pimpinan pada kartu Tim Manajemen (fallback inisial nama) |
| Aset statis | `public/css/*.css` (token desain + halaman), Tailwind dimuat dari CDN |

---

## 3. Teknologi & Versi

| Komponen | Versi / catatan |
|---|---|
| PHP | **8.5.9** (minimum 8.4), ekstensi `pdo_pgsql` |
| Laravel Framework | **12.69.3** |
| PostgreSQL | **18.6** |
| Tailwind CSS | via **CDN** (tanpa proses build) |
| Composer | 2.x |
| Node.js / Vite | **tidak diperlukan** — gaya ditulis di `public/css/*.css`, Tailwind dari CDN di `resources/views/partials/tailwind.blade.php` |

**Catatan penting untuk pengembang:** repositori masih menyimpan `vite.config.js` bawaan
Laravel, tetapi aplikasi **tidak** memakai `@vite`. Jadi `npm install` / `npm run dev`
tidak dibutuhkan.

---

## 4. Cara Menjalankan

Butuh PHP 8.5+, PostgreSQL, dan Composer. Langkahnya (berlaku di folder mana pun):

```bash
# 1. Dependensi
composer install

# 2. Konfigurasi
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```

Isi koneksi database di `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cv_maju_berkah
DB_USERNAME=<user kamu>
DB_PASSWORD=<password kamu>
```

```bash
# 3. Skema database + data contoh (1 perusahaan, 2 akun demo, 7 lowongan)
php artisan migrate --seed

# 4. Symlink penyimpanan (WAJIB agar logo unggahan & CV tampil)
php artisan storage:link

# 5. Jalankan
php artisan serve
```

Buka <http://127.0.0.1:8000>.

### Akun demo

| Peran | Email | Password |
|---|---|---|
| Admin Perusahaan | `admin@majuberkah.test` | `password` |
| Pencari Kerja | `seeker@majuberkah.test` | `password` |

### Uji cepat

```bash
php artisan test      # harus 77 test lulus / 413 assertion
```

### Database untuk testing

Test memakai `RefreshDatabase` dengan database terpisah **`cv_maju_berkah_testing`**
(diatur di `phpunit.xml`), jadi database dev tidak tersentuh.

---

## 5. Struktur Direktori

```
cv-maju-berkah/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/      Login, Register, Logout
│   │   │   ├── Public/    HomeController, CareerController  (beranda, karier, lamaran)
│   │   │   ├── Admin/     Dashboard, Job, Application, Setting, Partnership,
│   │   │   │              Feedback, Project, ActivityLog
│   │   │   └── Seeker/    Dashboard, Job, Application, Profile
│   │   └── Middleware/EnsureUserHasRole.php   pemisah area company_admin vs job_seeker
│   └── Models/             User, Company, Job, JobApplication, JobSeekerProfile,
│                           Partnership, Project, Feedback, ActivityLog
├── database/
│   ├── migrations/         12 migrasi (8 untuk tabel aplikasi)
│   └── seeders/            DatabaseSeeder, CompanySeeder, UserSeeder, JobSeeder
├── public/
│   ├── css/               tokens.css (token desain), home.css, pages.css, auth.css
│   └── images/            logo.*, team-meeting.jpg, team/*.jpg
├── resources/views/        54 berkas blade: layouts/, partials/, components/,
│                           home/, auth/, careers/, company/, job-seeker/, errors/
├── routes/                 web.php (semua 57 route aplikasi)
├── tests/                  Feature/ (6 berkas) + Unit/ + TestCase.php
├── docs/
│   ├── LAPORAN_IMPLEMENTASI.md         laporan audit & keputusan (464 baris)
│   └── pembagian-kerja/                indeks + 4 berkas ruang lingkup bagian
├── scratch/                            2 skrip bantu isi data awal (tidak ter-track Git)
├── README.md                           panduan instalasi & fitur
├── CHANGELOG.md                        riwayat perubahan (Keep a Changelog)
└── RIWAYAT_PERUBAHAN.txt               tabel seluruh commit dari awal
```

**View per area (54 berkas blade):** `company/` 12 · `job-seeker/` 10 · `home/` 6 ·
`components/` 5 · `errors/` 4 · `layouts/` 4 · `partials/` 4 · `careers/` 3 · `auth/` 2 ·
4 berkas di akar `views/`.

---

## 6. Peran & Hak Akses

Pemisahan dilakukan oleh middleware `role` (`EnsureUserHasRole`), bukan hanya disembunyikan di
tampilan — mencoba URL area lain akan ditolak **403**.

### 6.1 Pengunjung publik (tanpa login)
- Membuka beranda, tentang, layanan, kontak, dan **katalog lowongan** + detail lowongan.
- **Melamar lowongan** — diarahkan login/daftar dulu (URL tujuan diingat lewat `intended`).
- Mengirim **pengajuan kerja sama** dari halaman kontak.

### 6.2 Pencari Kerja (`job_seeker`)
- Dashboard ringkasan lamaran.
- Katalog lowongan versi portal + detail + melamar.
- **Profil & berkas**: data diri, pendidikan, keterampilan, unggah CV.
- **Lamaran saya**: melihat status, **mengubah** atau **menarik** lamaran **selama berstatus
  `pending`**.
- Mengirim **feedback/masukan** layanan.
- Membaca status hasil seleksi yang ditetapkan admin.

### 6.3 Admin Perusahaan (`company_admin`)
- Dashboard ringkasan (jumlah lowongan, lamaran, perlu review).
- **CRUD lowongan** + buka/tutup lowongan (toggle status).
- **Tinjau lamaran**: daftar, detail, unduh CV, set status seleksi + catatan review.
- **Profil perusahaan**: identitas, kontak, logo.
- Modul tambahan: **Kerja Sama**, **Project**, **Feedback**, **Log Aktivitas**.

### 6.4 Batas kepemilikan data (anti-IDOR)
Semua aksi admin dibatasi ke perusahaan milik admin yang login
(`$request->user()->company?->id`, dengan fallback perusahaan tunggal), dan semua aksi lamaran
pelamar diverifikasi kepemilikannya. Contoh: pelamar A tidak dapat melihat/menarik lamaran
pelamar B (403).

---

## 7. Basis Data

Total **16 tabel**: 8 tabel aplikasi + 8 tabel bawaan Laravel
(`users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `queue_jobs`,
`job_batches`, `failed_jobs`).

### 7.1 Tabel aplikasi

| Tabel | Model | Isi & penjelasan |
|---|---|---|
| `companies` | `Company` | Profil perusahaan (nama, alamat, kontak, visi/misi, layanan, `logo_path`). Aplikasi berjalan dengan **satu perusahaan aktif** (`Company::current()`), tapi struktur tetap mendukung banyak baris |
| `users` | `User` | Akun login, punya kolom `role` = `company_admin` / `job_seeker` dan relasi opsional ke `company_id` |
| `job_seeker_profiles` | `JobSeekerProfile` | Profil pelamar 1-ke-1 dengan user: data diri, pendidikan, keterampilan, `cv_path` |
| `jobs` | `Job` | Lowongan: judul, `slug`, kategori, tipe pekerjaan, lokasi, rentang gaji, deadline, deskripsi, kualifikasi, status (`draft`/`active`/`closed`) |
| `applications` | `JobApplication` | **Lamaran**: snapshot data pelamar saat melamar (`applicant_name/_email/_phone/_address`, `education`, `skills`, `cover_letter`), `cv_path`, `status`, `review_note`, `withdrawn_at` |
| `activity_logs` | `ActivityLog` | Jejak audit tindakan admin (aksi + modul + deskripsi) |
| `partnerships` | `Partnership` | Pengajuan kerja sama dari halaman publik + status review & catatan admin |
| `projects` | `Project` | Portofolio/pengerjaan project: klien, kategori, progres (%), anggaran, status |
| `feedbacks` | `Feedback` | Masukan pengguna: kategori, rating, pesan, status tindak lanjut |

### 7.2 Relasi utama

```
User (company_admin) ─┐
                      └─ Company ──(1:N)── Job ──(1:N)── JobApplication ──(N:1)── User (job_seeker)
User (job_seeker) ──(1:1)── JobSeekerProfile
Partnership ──(1:N)── Project
User ──(1:N)── Feedback
```

### 7.3 Constraint & index penting pada `applications`
```sql
CHECK  (status::text = ANY (ARRAY['pending','processing','accepted','rejected']))
NOT NULL job_seeker_id  (FK -> users.id ON DELETE CASCADE)
UNIQUE (job_id, job_seeker_id) WHERE withdrawn_at IS NULL   -- cegah lamaran ganda
```
- Status lama (`menunggu`, `interview`) **tidak valid** dan sudah dibersihkan dari model,
  controller, view, dan dikunci `CHECK` constraint.
- Lambda `UNIQUE` yang bersifat parsial (`WHERE withdrawn_at IS NULL`) membuat pelamar
  **boleh melamar ulang** ke lowongan yang sama setelah menarik lamarannya.
- `job_seeker_id` pernah `nullable`, lalu dijadikan **NOT NULL** lewat migrasi
  **non-destruktif** (data legacy dibersihkan lebih dulu; tanpa `migrate:fresh`).

### 7.4 Mengapa data pelamar disimpan ganda di `applications`
Kolom `applicant_*`, `education`, dan `skills` adalah **snapshot** kondisi pelamar saat
melamar. Tujuannya agar riwayat lamaran tetap utuh walau profil pelamar diubah setelahnya —
mis. pelamar memperbarui CV/alamat, lamaran lama tetap menyimpan versi yang dulu dikirim.

---

## 8. Peta Route (57 route)

Dihasilkan/diverifikasi dengan `php artisan route:list`. Total 60 entri, **3 di antaranya
bawaan framework** (`GET /storage/{path}`, `PUT /storage/{path}`, `GET /up`) → **57 route aplikasi**.

Rekap per area:

| Area | Prefix | Middleware | Jumlah |
|---|---|---|---|
| Publik | `/`, `/about`, `/services` | — | 3 |
| Kontak | `/contact` | — | 2 |
| Karier publik | `/careers` | — | 4 |
| Auth | `/login`, `/register`, `/logout` | `guest` / `auth` | 5 |
| Pencari kerja | `/job-seeker/*` | `auth`, `role:job_seeker` | 18 |
| Admin perusahaan | `/company/*` | `auth`, `role:company_admin` | 25 |
| **Total** | | | **57** |

### 8.1 Daftar lengkap

**Publik & karier (9)**
| Method | URI | Nama route |
|---|---|---|
| GET | `/` | `home` |
| GET | `/about` | `about` |
| GET | `/services` | `services` |
| GET | `/contact` | `contact` |
| POST | `/contact/partnership` | `contact.partnership` |
| GET | `/careers` | `careers.index` |
| GET | `/careers/{job}` | `careers.show` |
| GET | `/careers/{job}/apply` | `careers.apply.create` |
| POST | `/careers/{job}/apply` | `careers.apply` |

**Autentikasi (5)**
| Method | URI | Nama route |
|---|---|---|
| GET | `/login` | `login` |
| POST | `/login` | `login.store` |
| GET | `/register` | `register` |
| POST | `/register` | `register.store` |
| POST | `/logout` | `logout` |

**Pencari kerja — 18 route**
| Method | URI | Nama route |
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

**Admin perusahaan — 25 route**
| Method | URI | Nama route |
|---|---|---|
| GET | `/company/dashboard` | `company.dashboard` |
| GET | `/company/jobs` | `company.jobs.index` |
| GET | `/company/jobs/create` | `company.jobs.create` |
| POST | `/company/jobs` | `company.jobs.store` |
| GET | `/company/jobs/{job}` | `company.jobs.show` |
| GET | `/company/jobs/{job}/edit` | `company.jobs.edit` |
| PUT | `/company/jobs/{job}` | `company.jobs.update` |
| DELETE | `/company/jobs/{job}` | `company.jobs.destroy` |
| PATCH | `/company/jobs/{job}/toggle-status` | `company.jobs.toggle` |
| GET | `/company/applications` | `company.applications.index` |
| GET | `/company/applications/{jobApplication}` | `company.applications.show` |
| PATCH | `/company/applications/{jobApplication}/status` | `company.applications.status` |
| GET | `/company/applications/{jobApplication}/cv` | `company.applications.cv` |
| GET | `/company/profile` | `company.profile.edit` |
| PUT | `/company/profile` | `company.profile.update` |
| GET | `/company/partnership` | `company.partnership.index` |
| PATCH | `/company/partnership/{partnership}/status` | `company.partnership.status.update` |
| GET | `/company/project` | `company.project.index` |
| POST | `/company/project` | `company.project.store` |
| PUT | `/company/project/{project}` | `company.project.update` |
| DELETE | `/company/project/{project}` | `company.project.destroy` |
| GET | `/company/feedback` | `company.feedback.index` |
| PATCH | `/company/feedback/{feedback}/status` | `company.feedback.status.update` |
| DELETE | `/company/feedback/{feedback}` | `company.feedback.destroy` |
| GET | `/company/activity-log` | `company.activity-log.index` |

---

## 9. Fitur & Penjelasan

### 9.1 Halaman publik
- **Beranda** — hero, ringkasan layanan, lowongan terbaru, section "Tentang Kami" (dengan foto tim).
- **Tentang** — profil perusahaan, visi & misi, kartu Tim Manajemen (foto pimpinan + fallback inisial).
- **Layanan** — daftar layanan perusahaan distribusi.
- **Kontak** — alamat, kontak, dan **formulir pengajuan kerja sama** (tersimpan di tabel `partnerships`).
- **Karier** — katalog lowongan dengan **pencarian kata kunci** dan **filter** kategori, lokasi,
  tipe pekerjaan, urutan (Terbaru / Deadline Terdekat / Judul A-Z), plus tombol Reset.
  Detail lowongan menampilkan deskripsi, kualifikasi, rentang gaji, dan deadline.

### 9.2 Portal Pencari Kerja
- **Dashboard** — ringkasan lamaran & statusnya.
- **Cari lowongan** — katalog + filter yang sama seperti halaman publik.
- **Melamar** — formulir lamaran; email akun terkunci (tidak bisa memakai email orang lain),
  unggah CV, dan pencegahan **lamaran ganda** ke lowongan yang sama.
- **Lamaran Saya** — daftar lamaran + status; pelamar dapat **mengubah** atau **menarik**
  lamaran **selama status masih `pending`**.
- **Profil & Berkas** — CRUD profil (data diri, pendidikan, keterampilan) + CV.
- **Feedback** — mengirim masukan atas layanan portal.

### 9.3 Portal Admin Perusahaan
- **Dashboard** — kartu statistik (total lowongan, lowongan aktif, total lamaran, perlu review)
  + daftar lamaran terbaru & lowongan aktif. "Lowongan Aktif" = lowongan yang benar-benar
  terbuka untuk publik; bila ada lowongan aktif yang deadline-nya sudah lewat, muncul
  **badge "n kedaluwarsa"**.
- **CRUD Lowongan** — tambah/edit/hapus, buka-tutup lowongan (toggle). Lowongan yang sudah
  punya lamaran **tidak dihapus permanen**, melainkan diubah statusnya menjadi `closed`
  (menjaga integritas riwayat lamaran).
- **Tinjau Lamaran** — daftar + filter, detail pelamar, unduh CV, atur **status seleksi** dan
  **catatan review** untuk pelamar.
- **Profil Perusahaan** — identitas, kontak, dan unggah **logo** (png/jpg/jpeg/webp/svg, maks 2 MB).
- **Modul tambahan** (di luar blueprint, dipertahankan sebagai nilai tambah):
  | Modul | Fungsi |
  |---|---|
  | Kerja Sama | Tinjau pengajuan kerja sama dari publik: setujui / arsipkan + catatan admin |
  | Project | Kelola portofolio project: data klien, progres %, anggaran, status |
  | Feedback | Baca masukan pengguna, tandai "ditindaklanjuti", hapus |
  | Log Aktivitas | Jejak audit otomatis atas tindakan admin (buat/ubah/hapus/ubah status) |

### 9.4 Detail perilaku yang perlu diketahui
- **Detail lowongan yang sudah tutup/kedaluwarsa tetap bisa dibuka (HTTP 200)** dengan label
  "Lowongan Ditutup" dan tombol lamar disembunyikan; backend **tetap menolak** lamaran (403).
  Ini **deviasi sengaja dari BR-10** (lihat bab 14) agar riwayat lowongan tetap dapat diakses.
- **Lowongan dengan lamaran tidak dihapus permanen** — statusnya diubah menjadi `closed`.
- **Rate limit** dipasang pada `POST /login` dan `POST /contact/partnership` (`throttle:10,1`).
- **CV disimpan di disk `local`** (bukan publik); unduhan hanya lewat route terotorisasi.

---

## 10. Kontrak Status (dan alasan di baliknya)

Aplikasi ini punya empat jenis "status" yang punya aturan perpindahan. Aturannya ditaruh
**di dalam model** (bukan hanya di tampilan) supaya backend dan UI selalu konsisten dan tidak
bisa ditembus lewat permintaan HTTP langsung.

```
Lamaran     : pending -> processing -> accepted | rejected
              accepted & rejected = FINAL (tidak dapat diubah lagi)

Kerja sama  : pending -> approved -> archived
              approved tidak dapat kembali ke pending; archived = FINAL

Feedback    : Baru -> Ditindaklanjuti            (satu arah)

Project     : planning -> ongoing -> completed   (satu arah; completed = FINAL)
```

Penjelasan & alasan:

| Status | Aturan | Alasan |
|---|---|---|
| **Lamaran** | Hanya admin yang boleh mengubah. `accepted`/`rejected` bersifat **final**; lamaran yang ditarik (`withdrawn_at`) tidak dapat diubah lagi. Menyimpan status yang sama tetap boleh | Permintaan pemilik produk: keputusan seleksi tidak boleh "bolak-balik" (mis. pelamar yang sudah diterima tidak bisa dikembalikan ke tahap proses). Tetap boleh menyimpan status sama agar admin bisa menambah catatan tanpa mengubah keputusan |
| **Kerja sama** | `approved` tidak dapat kembali ke `pending`; `archived` final | Kemitraan yang sudah resmi tidak masuk akal bila dikembalikan ke "menunggu review" |
| **Feedback** | Satu arah `Baru` → `Ditindaklanjuti` | Jejak "sudah ditangani" tidak boleh direset, agar tidak menutupi masukan yang belum selesai |
| **Project** | Satu arah; `completed` final; saat diubah ke `completed`, progres **otomatis 100%** | Mencegah data janggal seperti "Selesai tetapi progres 60%" |

**Implementasi teknis:**
- Model `JobApplication`, `Partnership`, `Project` menyimpan `ALLOWED_TRANSITIONS`,
  method `allowedTransitionsFrom()` dan `canTransitionTo()`; `Feedback` memakai
  `isFollowedUp()`.
- Controller menolak transisi tidak sah dengan pesan ramah **tanpa mengubah data**.
- Tampilan hanya menampilkan status tujuan yang sah; pada lamaran yang sudah final,
  dropdown terkunci dan muncul papan **"Keputusan Final"** (catatan tetap bisa ditambah).
- Pengecekan transisi dijalankan **setelah** validasi, supaya nilai status ngawur
  (mis. `interview`) tetap dilaporkan sebagai **kesalahan validasi**, bukan pesan transisi.

---

## 11. Aturan Bisnis yang Diterapkan

| Kode | Aturan | Wujud di aplikasi |
|---|---|---|
| BR-03 | Lamaran hanya boleh masuk ke lowongan yang masih aktif & belum lewat deadline | Guard `isOpenForApplication()` → 403 bila melanggar |
| BR-08 | Admin hanya boleh mengelola data perusahaan miliknya | Scoping lewat `companyId()` + `abort_unless(..., 403)` di semua aksi admin |
| BR-10 | *(deviasi disetujui)* Detail lowongan tutup/kedaluwarsa tetap 200, tetapi tidak bisa dilamar | Banner "Lowongan Ditutup" + backend menolak 403 |

Selain itu:
- **Lamaran ganda diblokir** (unique parsial per lowongan/pelamar).
- **Data pelamar disnapshot** saat melamar agar riwayat utuh.
- **Status lamaran hanya bisa diubah admin**, dan tidak ada status legacy
  (`menunggu`/`interview`) yang diterima.

---

## 12. Keamanan & Perlindungan Data

| Aspek | Implementasi |
|---|---|
| Pemisahan peran | Middleware `role:company_admin` / `role:job_seeker` (bukan hanya sembunyikan menu) |
| Anti-IDOR | Verifikasi kepemilikan pada semua aksi lamaran & aksi admin per perusahaan |
| Unggahan berkas | Validasi tipe & ukuran (logo maks 2 MB; CV dibatasi tipe PDF/dokumen) |
| CV pelamar | Disimpan di disk `local` (tidak dapat diakses langsung via URL publik); unduhan lewat route terotorisasi |
| Rate limiting | `throttle:10,1` pada login dan form kerja sama |
| Pesan error | `APP_DEBUG=false` pada demo (tidak menampilkan stack trace), halaman error kustom 403/404/419/500 |
| Akun demo | Password sederhana `password` hanya untuk demo — wajib diganti di lingkungan nyata |

---

## 13. Pengujian

Suite otomatis: **77 test / 413 assertion — semuanya lulus** (`php artisan test`).

| Berkas test | Test | Cakupan |
|---|---|---|
| `tests/Feature/CareerApplicationTest.php` | 23 | Alur lamaran menyeluruh: katalog, form, kirim lamaran, lamaran ganda, IDOR, CRUD profil, kontrol akses, halaman error |
| `tests/Feature/StatusTransitionTest.php` | 19 | Kontrak transisi status (lamaran, kerja sama, feedback, project), batas nilai gaji, deadline kondisional, badge kedaluwarsa |
| `tests/Feature/FullRouteSmokeTest.php` | 15 | **Smoke test** seluruh 60 route × 3 peran + semua alur tulis (deteksi error 500), termasuk alur lowongan/lamaran/profil/logo |
| `tests/Feature/SecurityAndAccessTest.php` | 14 | Keamanan per peran, aturan ubah/tarik lamaran, filter pencarian, profil perusahaan |
| `tests/Feature/BusinessModulesTest.php` | 4 | Modul tambahan: kerja sama, project, feedback |
| `tests/Feature/ExampleTest.php` + `tests/Unit/ExampleTest.php` | 2 | Uji bawaan Laravel (aplikasi merespons) |
| **Total** | **77** | |

Selain test otomatis, verifikasi akhir meliputi:
- **Sapuan HTTP nyata**: 20 halaman + 70 tautan/aset unik → **0 gagal**.
- **Smoke test 60 route × 3 peran** (guest, admin, pelamar) → **nol error 500**.
- `php artisan view:cache` berhasil — memastikan seluruh view dapat dikompilasi (sebelumnya
  gagal karena satu view mati `karir.blade.php`, kini dihapus).

---

## 14. Keputusan Desain & Deviasi

| # | Keputusan | Alasan |
|---|---|---|
| 1 | Tema terang + aksen biru `#3B82F6` (deviasi blueprint §11.1) | Arahan pemilik produk melalui style guide tersendiri |
| 2 | Tailwind via **CDN**, bukan Vite | Menyederhanakan setup (tanpa `npm install`) |
| 3 | Kolom tambahan pada `applications` (snapshot pelamar, `withdrawn_at`) | Riwayat lamaran tetap utuh walau profil berubah |
| 4 | Entitas di luar blueprint dipertahankan (Partnership, Project, Feedback, ActivityLog, Setting) | Nilai tambah untuk pemilik produk |
| 5 | Route tambahan di luar blueprint (portal pelamar, unduh CV, toggle status) | Melengkapi alur yang blueprint minta secara fungsional |
| 6 | Renaming penuh ke kontrak blueprint (Fase B) | Blueprint dinyatakan sumber kebenaran |
| 7 | `job_seeker_id` wajib (NOT NULL) + FK `ON DELETE CASCADE` | Sesuai §7.5, dimigrasikan non-destruktif |
| 8 | Update lamaran memakai `PATCH` | Sesuai §9.3 dan kontrak REST |
| 9 | `APP_DEBUG=false` untuk demo | Sesuai §12; `phpunit.xml` tetap `true` untuk diagnostik test |
| 10 | Detail lowongan `closed`/kedaluwarsa tetap 200 | **Deviasi sengaja dari BR-10** — riwayat lowongan tetap dapat diakses/untuk SEO; lamaran tetap diblokir 403 |
| 11 | Halaman error 422 tidak dibuat | Blueprint menyebut "jika dibutuhkan"; kegagalan validasi dikembalikan ke form beserta pesan. Yang tersedia: 403, 404, 419, 500 |
| 12 | Status final tidak dapat dikembalikan | Permintaan pemilik produk (lihat bab 10) |
| 13 | Gaji dibatasi **9.999.999.999** | Mengikuti kapasitas kolom `decimal(12,2)`; melampaui itu ditolak sebagai kesalahan validasi, bukan error 500 |
| 14 | Deadline lowongan divalidasi **kondisional** saat edit | Nilai lama boleh dipertahankan (lowongan lama tetap bisa dirapikan), nilai baru wajib setelah hari ini |
| 15 | Status "Selesai" menyetel progres 100% | Mencegah data janggal "Selesai tetapi 60%" |
| 16 | "Lowongan Aktif" dashboard = terbuka untuk publik | Konsisten dengan katalog & tabel lowongan; ditambah badge jumlah kedaluwarsa |

---

## 15. Pembagian Kerja 4 Bagian

Pekerjaan dibagi **4 bagian adil** mengikuti peran pada blueprint §13. Karena tim dibagi 4
(bukan 5), **Peran A digabung dengan E** — persis seperti yang disarankan blueprint
("jika anggota kurang dari lima orang: gabungkan Peran A dengan E").

| # | Branch | Bagian | Peran §13 | Route |
|---|---|---|---|---|
| 1 | `feature/1-auth-dan-integrasi` | Autentikasi, Integrasi & Dokumentasi | **A + E** | 12 |
| 2 | `feature/2-company-profile` | Company Profile & UI Publik | **B** | 11 |
| 3 | `feature/3-jobs-crud` | CRUD Lowongan Admin | **C** | 13 |
| 4 | `feature/4-applications` | Lamaran & Dashboard Pencari Kerja | **D** | 21 |

**57 route terbagi habis tanpa tumpang tindih** (12 + 11 + 13 + 21 = 57). Setiap branch berisi
**aplikasi utuh yang bisa dijalankan** (bukan potongan rusak), plus berkas penanda
`docs/pembagian-kerja/branch/<nama-bagian>.md` yang memuat ruang lingkup, daftar berkas yang
dipegang, langkah mulai, dan checklist tugas.

Salinan siap dibagikan tersedia di folder induk `cvmb-pembagian-kerja\` — setiap bagian
diletakkan di folder tersendiri (tanpa `vendor\` dan `.env`).

Aturan kerja sama (ringkas):
- Jangan mengedit berkas yang sama bersamaan tanpa koordinasi.
- Jangan membuat migrasi duplikat untuk tabel yang sama.
- Jangan commit `.env`, kredensial, password, token, atau CV pribadi.
- Commit kecil dengan pesan jelas; merge hanya setelah fitur diuji.

---

## 16. Riwayat Perubahan

Perubahan dikerjakan bertahap (±20 commit). Ringkas per fase
(tabel lengkap per commit ada di `RIWAYAT_PERUBAHAN.txt`):

| Fase | Isi |
|---|---|
| Fondasi | Migrasi + model database; UI halaman publik + formulir lamaran; portal admin & pelamar |
| Fase 1 — Bug kritis | Perbaikan kolom pencarian, pembersihan status legacy (`menunggu`/`interview`), perapian branding |
| Fase 2 — Selaras blueprint | Penamaan DB, model, dan route diseragamkan dengan kontrak blueprint |
| Fase 3 — Identitas visual | Tema terang + palet biru `#3B82F6`; tipografi diseragamkan ke Inter |
| Fase 4 — Aset | Logo berlapis tiga, foto tim, foto pimpinan, fallback statis |
| Fase 5 — Audit & pengujian | Smoke test seluruh route; perbaikan hasil audit: transaksi DB saat melamar, `PATCH`, `job_seeker_id` NOT NULL, `APP_DEBUG=false`, pembersihan stub |
| Fase 6 — Dokumentasi | `README.md` sesuai blueprint §15 dan `docs/LAPORAN_IMPLEMENTASI.md` |
| Pembagian kerja | `docs/pembagian-kerja/` + 4 branch dengan berkas penanda masing-masing |
| Audit lanjutan T1–T4 | Perbaikan atas laporan pemilik produk (lihat tabel di bawah) |

**Empat temuan yang dilaporkan pemilik produk dan perbaikannya:**

| Kode | Masalah | Perbaikan |
|---|---|---|
| T1 | Input gaji terlalu besar (mis. 299.991.100.000) membuat PostgreSQL menolak insert (`numeric field overflow`) dan muncul sebagai **error 500** | Batas `Job::SALARY_MAX_ALLOWED` (9.999.999.999) + validasi `max` + atribut `max` pada form → ditolak sebagai kesalahan validasi |
| T2 | Lamaran yang sudah `accepted` masih bisa dikembalikan ke `processing`/`rejected` (dan sebaliknya); pola serupa di modul lain | Kontrak transisi status di model + penolakan di controller + UI hanya menampilkan status sah |
| T3 | Saat mengedit lowongan, deadline bisa diisi ke masa lalu tanpa peringatan | Validasi kondisional: nilai lama boleh dipertahankan, nilai baru wajib setelah hari ini |
| T4 | Kartu "Lowongan Aktif" di dashboard menghitung lowongan yang sudah kedaluwarsa | Kartu memakai scope `publiclyVisible()` + badge "n kedaluwarsa" |

**Perkembangan jumlah test:** `0 → 58 → 72 → 77` (assertion `0 → 358 → 400 → 413`).

---

## 17. Definition of Done, Sisa Pekerjaan & Berkas Penting

### 17.1 Definition of Done (ringkas)
- [x] Seluruh fitur yang diminta blueprint berjalan (publik, pelamar, admin).
- [x] Peran & hak akses dipisahkan dan diuji (403 lintas area).
- [x] Kontrak status lamaran & transisi status lain ditegakkan backend + UI.
- [x] Penanganan unggahan (CV & logo) aman; CV tidak dapat diakses langsung.
- [x] Halaman error kustom tersedia (403/404/419/500); tidak ada stack trace ke pengunjung.
- [x] Diuji otomatis (77 test) **dan** smoke test seluruh route → nol error 500.
- [x] Dokumentasi: README, CHANGELOG, laporan implementasi, pembagian kerja, riwayat perubahan.

### 17.2 Sisa pekerjaan (opsional/penyempurnaan)
1. Penyempurnaan tampilan **mobile** pada beberapa tabel dashboard.
2. Penyempurnaan **copywriting** halaman publik bila diperlukan.
3. *(Opsional)* penghapusan berkas/folder sisa pengembangan bila memang tidak diperlukan.

### 17.3 Berkas penting & di mana harus membaca apa

| Kalau kamu ingin tahu... | Baca |
|---|---|
| Spesifikasi resmi yang mengikat | `PROJECT_BLUEPRINT_CV_MAJU_BERKAH.md` |
| Cara install, fitur, akun demo, peta route ringkas | `README.md` |
| Riwayat perubahan tingkat tinggi | `CHANGELOG.md` |
| Laporan audit lengkap, keputusan desain, status lingkungan | `docs/LAPORAN_IMPLEMENTASI.md` |
| Daftar seluruh commit + statistiknya | `RIWAYAT_PERUBAHAN.txt` |
| Pembagian tugas tim | `docs/pembagian-kerja/README.md` + 4 berkas bagian |
| Penjelasan menyeluruh (dokumen ini) | `DOKUMENTASI_SELURUH_PROJECT.md` |

---

*Dokumen ini disusun sebagai penjelasan menyeluruh; bila ada perbedaan dengan blueprint,
blueprint adalah sumber kebenaran dan perbedaan tersebut dicatat pada bab 14.*
