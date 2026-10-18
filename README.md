# Portal Karier CV Maju Berkah

Aplikasi web **profil perusahaan** sekaligus **portal rekrutmen** untuk **CV Maju Berkah**
(perusahaan distribusi barang & penyediaan kebutuhan usaha).

Dibangun dengan **Laravel 12**, **PostgreSQL**, **Tailwind CSS**, antarmuka **Bahasa Indonesia**,
dan zona waktu **Asia/Jakarta**.

---

## Fitur

### Publik (tanpa login)
Beranda, Tentang Kami, Layanan, Kontak (+ formulir pengajuan kerja sama),
katalog karier dengan pencarian & filter (kategori, lokasi, tipe pekerjaan, urutan),
serta detail lowongan. Katalog publik hanya menampilkan lowongan **aktif** yang **belum melewati deadline**.

### Pencari kerja (`job_seeker`)
Dashboard portal pelamar, jelajahi lowongan, kirim lamaran (unggah CV PDF),
ubah lamaran selama masih `pending`, tarik lamaran, pantau status seleksi,
kelola profil & CV, unduh CV, dan kirim ulasan layanan (feedback).

### Admin perusahaan (`company_admin`)
Dashboard ringkasan, CRUD lowongan (termasuk buka/tutup cepat),
review lamaran masuk + unduh CV pelamar, ubah status lamaran,
serta modul **kerja sama**, **feedback**, **project/portofolio**,
**log aktivitas**, dan **pengaturan profil perusahaan** (termasuk unggah logo).

### Fitur tambahan di luar blueprint
Blueprint §5/§9 hanya mewajibkan alur inti (profil perusahaan, lowongan, lamaran).
Entitas berikut dipertahankan sebagai nilai tambah dan didokumentasikan pada
[`docs/LAPORAN_IMPLEMENTASI.md`](docs/LAPORAN_IMPLEMENTASI.md):

| Modul | Ringkasan |
|---|---|
| **Partnership** (Kerja Sama) | Formulir pengajuan kerja sama dari halaman Kontak + pengelolaan status oleh admin |
| **Project** (Portofolio) | Daftar proyek beserta progres, anggaran, dan klien |
| **Feedback** (Ulasan Layanan) | Ulasan bintang 1–5 dari pencari kerja + tindak lanjut admin |
| **ActivityLog** (Jejak Audit) | Catatan aksi penting (login, CRUD lowongan, perubahan status lamaran) |
| **Setting** (Profil Perusahaan) | Visi, misi, layanan, alamat, kontak, dan unggah logo |

---

## Kontrak Status Lamaran

```
pending → processing → accepted | rejected
```

- Hanya keempat nilai di atas yang sah (dikunci juga oleh `CHECK` constraint di database).
- **Hanya admin** yang boleh mengubah status seleksi.
- **Keputusan bersifat final**: setelah `accepted` atau `rejected`, status **tidak dapat**
  dikembalikan ke `pending`/`processing` maupun ditukar ke keputusan lain. Admin masih dapat
  menambah/memperbarui catatan untuk pelamar.
- Pelamar hanya dapat **mengubah** atau **menarik** lamaran selama berstatus `pending`.
- Lamaran yang ditarik (`withdrawn_at` terisi) **tidak** menghalangi pelamar melamar ulang.

Kontrak transisi yang sama juga berlaku pada modul kerja sama (`pending → approved → archived`),
feedback (`Baru → Ditindaklanjuti`, satu arah), dan project (`planning → ongoing → completed`).

---

## Teknologi & Versi

| Komponen | Versi yang dipakai |
|---|---|
| PHP | **8.5.9** (minimum 8.4), ekstensi `pdo_pgsql` |
| Laravel Framework | **12.69.3** |
| PostgreSQL | **18.6** |
| Tailwind CSS | via **CDN** (tanpa proses build) |
| Composer | 2.x |
| Node.js | **tidak diperlukan** (lihat catatan di bawah) |

> **Catatan Node.js/Vite:** repositori masih menyimpan `vite.config.js` bawaan Laravel, tetapi
> aplikasi **tidak** memakai `@vite`. Seluruh gaya ditulis di `public/css/*.css` dan Tailwind
> dimuat dari CDN pada `resources/views/partials/tailwind.blade.php`. Jadi `npm install` /
> `npm run dev` tidak dibutuhkan.

---

## Kebutuhan Sistem

- PHP 8.5 dengan ekstensi `pdo_pgsql` dan `pgsql`
- Composer 2.x
- PostgreSQL 18 (14+ kemungkinan besar juga jalan)
- Node.js **opsional** (tidak dipakai saat ini)

> **Windows/Laragon:** pastikan `extension=pdo_pgsql` aktif di `php.ini`.
> Jika versi `php` di PATH bukan yang dipakai aplikasi, panggil PHP secara eksplisit, contoh:
> `C:\laragon\bin\php\php-8.5.9\php.exe artisan migrate`.
> (`php` polos pada mesin ini menunjuk PHP 8.3.35 sehingga pemeriksaan platform Composer gagal.)

---

## Instalasi

```bash
# 1. Dependensi
composer install

# 2. Konfigurasi lingkungan
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

APP_ENV=local
APP_DEBUG=false
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
```

> Zona waktu **Asia/Jakarta** diatur langsung di `config/app.php` (`'timezone' => 'Asia/Jakarta'`),
> bukan melalui `.env`.

```bash
# 3. Skema database + data contoh (termasuk akun demo & 7 lowongan)
php artisan migrate --seed

# 4. Symlink penyimpanan (WAJIB agar logo hasil unggahan dapat ditampilkan)
php artisan storage:link

# 5. Jalankan aplikasi
php artisan serve
```

Aplikasi berjalan di <http://127.0.0.1:8000>.

> **Catatan Laragon:** virtual host `cv-maju-berkah.test` pada mesin ini salah konfigurasi
> (`DocumentRoot` menunjuk folder induk sehingga hanya menampilkan daftar direktori).
> Gunakan `php artisan serve`, atau URL
> `http://localhost/cv-maju-berkah/cv-maju-berkah/public/`.

---

## Akun Demo

Dibuat oleh `database/seeders/UserSeeder.php`:

| Peran | Email | Password |
|---|---|---|
| Admin Perusahaan | `admin@majuberkah.test` | `password` |
| Pencari Kerja | `seeker@majuberkah.test` | `password` |

> Kredensial di atas adalah data dummy untuk demo lokal. Jangan dipakai di produksi.

---

## Tema & Design System

- **Tema terang dengan aksen biru `#3B82F6`**, tipografi **Inter**.
  Ini adalah **deviasi yang disetujui pemilik produk** dari saran tema gelap
  charcoal/terracotta pada blueprint §11.1 (dokumentasi lengkap ada di
  [`docs/LAPORAN_IMPLEMENTASI.md`](docs/LAPORAN_IMPLEMENTASI.md)).
- `public/css/tokens.css` adalah **sumber tunggal** token desain: warna semantik, token
  kompatibilitas Material 3, skala tipografi, spacing, radius, dan komponen bersama
  (`.btn-*`, `.badge`, `.alert`, `.empty-state`, `.pagination`, `.form-*`).
- `public/css/home.css`, `pages.css`, dan `auth.css` memakai token tersebut — tidak ada lagi
  blok `:root` yang menduplikasi variabel.
- Konfigurasi Tailwind (CDN) ada di `resources/views/partials/tailwind.blade.php` dan disamakan
  dengan token agar dashboard dan halaman publik konsisten.

---

## Identitas & Aset

### Logo
Logo memakai partial bersama `resources/views/partials/brand-mark.blade.php`
(dipakai navbar, footer, halaman login/register, dan kedua sidebar dashboard)
dengan **prioritas berlapis**:

1. Logo yang **diunggah admin** melalui *Pengaturan → Profil Perusahaan* (`companies.logo_path`,
   tersimpan di disk `public`, folder `logos/`).
2. Berkas **statis** `public/images/logo.png` (atau `.jpg`/`.svg`) bila belum ada unggahan.
3. **Monogram "MB"** sebagai cadangan terakhir.

**Cara mengganti logo:** login sebagai admin → **Pengaturan** → unggah pada kolom logo →
Simpan. Format yang diterima `png, jpg, jpeg, webp, svg`, ukuran maksimal **2 MB**.
Mengganti berkas `public/images/logo.png` juga cukup untuk mengubah logo default tanpa login.

> Unggahan logo memerlukan `php artisan storage:link` (langkah 4 instalasi).

### Foto
| Berkas | Ukuran | Dipakai di |
|---|---|---|
| `public/images/logo.png` | 523×431 (transparan) | Fallback logo statis |
| `public/images/team-meeting.jpg` | 1376×768 | Section "Tentang Kami" pada beranda **dan** halaman `/about` |
| `public/images/team/hendra-wijaya.jpg` | 640×640 | Kartu pimpinan (Tentang Kami) |
| `public/images/team/ratna-dewi.jpg` | 640×640 | Kartu pimpinan |
| `public/images/team/budi-santoso.jpg` | 640×640 | Kartu pimpinan |
| `public/images/team/siti-aminah.jpg` | 640×640 | Kartu pimpinan |

Foto pimpinan dipotong persegi (center crop) agar memenuhi kartu secara penuh. Bila berkas foto
tidak tersedia, kartu menampilkan **inisial nama** sebagai cadangan.

---

## Struktur Direktori Penting

```
app/Http/Controllers/
├── Auth/       Login, Register, Logout
├── Public/     HomeController, CareerController (beranda, karier, lamaran)
├── Admin/      Dashboard, Job, Application, Setting, Partnership, Feedback, Project, ActivityLog
└── Seeker/     Dashboard, Job, Application, Profile

app/Models/      User, Company, Job, JobApplication, JobSeekerProfile,
                 Partnership, Project, Feedback, ActivityLog

app/Http/Middleware/EnsureUserHasRole.php   pemisah area company_admin vs job_seeker
public/css/      tokens.css (token desain), home.css, pages.css, auth.css
resources/views/ partials/ (brand-mark, tailwind, sidebar, footer), auth/, careers/,
                 company/, job-seeker/, dan halaman publik
```

---

## Peta Route (ringkas)

Total **57 route aplikasi**. Peta lengkap ada di
[`docs/LAPORAN_IMPLEMENTASI.md` §7](docs/LAPORAN_IMPLEMENTASI.md).

| Area | Prefix | Middleware |
|---|---|---|
| Publik | `/`, `/about`, `/services`, `/contact`, `/careers` | — |
| Auth | `/login`, `/register`, `/logout` | `guest` / `auth` |
| Pencari kerja | `/job-seeker/*` | `auth`, `role:job_seeker` |
| Admin perusahaan | `/company/*` | `auth`, `role:company_admin` |

Contoh nama route: `careers.index`, `careers.show`, `careers.apply`,
`job-seeker.applications.index`, `company.jobs.store`, `company.applications.status`.

---

## Pengujian

```bash
php artisan test                          # seluruh suite
php artisan test tests/Feature/FullRouteSmokeTest.php   # satu berkas
```

Status saat ini: **77 test lulus / 413 assertion**.

| Berkas | Cakupan |
|---|---|
| `tests/Feature/CareerApplicationTest.php` | Alur lamaran publik: katalog, form, kirim lamaran, lamaran ganda, IDOR, CRUD profil |
| `tests/Feature/BusinessModulesTest.php` | Modul bisnis: project, feedback, kerja sama |
| `tests/Feature/SecurityAndAccessTest.php` | Keamanan akses per peran, aturan ubah/tarik lamaran, filter, profil perusahaan |
| `tests/Feature/FullRouteSmokeTest.php` | **Smoke test** seluruh 60 route × 3 peran + semua alur tulis (deteksi 500) |
| `tests/Feature/StatusTransitionTest.php` | **Kontrak transisi status** (lamaran, kerja sama, feedback, project) + batas gaji |

Test memakai `RefreshDatabase` dan database terpisah `cv_maju_berkah_testing`
(diatur di `phpunit.xml`), jadi database dev tidak tersentuh.

---

## Definition of Done

Sebuah fitur dianggap selesai bila seluruh poin berikut terpenuhi
(ringkasan blueprint §15, disesuaikan dengan kondisi proyek):

- [x] Berfungsi pada alur normal
- [x] Validasi input berjalan di server (Form Request / `$request->validate()`)
- [x] Pesan sukses dan gagal tampil (Bahasa Indonesia)
- [x] Hak akses benar (middleware `role` + pemeriksaan kepemilikan data/IDOR)
- [x] Tidak menimbulkan error pada fitur lain (dibuktikan smoke test seluruh route)
- [x] Tampilan responsif dan konsisten (satu sumber token desain)
- [x] Diuji melalui test otomatis (77 test) **dan** smoke test seluruh route
- [x] Kode sudah di-commit pada branch `feature/dashboard-admin-pelamar`
- [x] Dokumentasi diperbarui bila ada kontrak yang berubah (dokumen ini + laporan implementasi)

Checklist pengujian blueprint §16 tercentang satu per satu pada
[`docs/LAPORAN_IMPLEMENTASI.md` §8](docs/LAPORAN_IMPLEMENTASI.md).

---

## Catatan Produksi & Keamanan

1. **`APP_DEBUG=false`** saat demo/produksi (nilai ini sudah diset di `.env` lokal).
   Setel `true` hanya saat mengembangkan agar stack trace terlihat.
   `phpunit.xml` menyetel `APP_DEBUG=true` khusus untuk test agar diagnostik lengkap.
2. **Ganti password database** (`cvmb_app`) dan simpan kredensial di luar repositori.
   `.env` sudah masuk `.gitignore` — jangan pernah commit kredensial atau berkas CV pelamar.
3. Simpan CV pelamar di disk `local` (bukan publik). Unduhan hanya lewat route terotorisasi
   (`company.applications.cv`, `job-seeker.applications.cv`, `job-seeker.profile.cv`).
4. Jalankan `php artisan optimize:clear` setelah mengubah `.env`/`config`, atau
   `php artisan optimize` untuk produksi (config/route/view cache).
5. Rate limit sudah aktif untuk `POST /login` dan `POST /contact/partnership` (`throttle:10,1`).

---

## Dokumentasi Lanjutan

[`docs/LAPORAN_IMPLEMENTASI.md`](docs/LAPORAN_IMPLEMENTASI.md) memuat laporan audit
kesesuaian dengan blueprint, daftar perubahan per fase, keputusan desain, peta route lengkap,
status lingkungan, dan sisa pekerjaan.

[`CHANGELOG.md`](CHANGELOG.md) mencatat riwayat perubahan penting (format Keep a Changelog).

## Lisensi

Proprietary — internal CV Maju Berkah.
