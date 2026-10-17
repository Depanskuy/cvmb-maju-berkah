# Bagian 2 — Company Profile & UI Publik

| | |
|---|---|
| **Branch** | `feature/2-company-profile` |
| **Peran blueprint §13** | **B** (Company Profile dan UI Publik) |
| **Jumlah route** | 11 dari 57 |

## Ruang lingkup

1. **Halaman publik** — Beranda, Tentang Kami, Layanan, Kontak.
2. **Komponen visual bersama** — navbar, footer, brand-mark (logo berlapis), konfigurasi Tailwind CDN,
   dan **design token** (`public/css/tokens.css`) sebagai satu-satunya sumber warna/tipografi/spacing.
3. **Katalog & detail lowongan publik** — pencarian + filter (kategori, lokasi, tipe pekerjaan,
   urutan), hanya menampilkan lowongan **aktif** dan **belum kedaluwarsa**.
4. **Profil perusahaan (sisi admin)** — pengaturan profil termasuk unggah logo.
5. **Pengajuan kerja sama** — formulir dari halaman Kontak + pengelolaan status oleh admin.
6. **Aset** — logo statis dan foto tim.

> ⚠️ Bagian ini **tidak boleh** mengubah schema `jobs` tanpa koordinasi dengan Bagian 3 (pemilik CRUD lowongan).

## Berkas yang dipegang

| Berkas | Keterangan |
|---|---|
| `app/Http/Controllers/Public/HomeController.php` | Beranda, tentang, layanan, kontak |
| `app/Http/Controllers/Public/CareerController.php` | Katalog & detail publik *(bersama Bagian 4: `apply`/`store`)* |
| `app/Http/Controllers/Admin/SettingController.php` | Profil perusahaan + unggah logo |
| `app/Http/Controllers/Admin/PartnershipController.php` | Pengelolaan status kerja sama |
| `app/Models/Partnership.php` | Model kerja sama |
| `resources/views/home.blade.php`, `about.blade.php`, `services.blade.php`, `contact.blade.php` | Halaman publik |
| `resources/views/home/*` | `hero`, `stats`, `services`, `featured-jobs`, `about`, `cta` |
| `resources/views/careers/index.blade.php`, `careers/show.blade.php` | Katalog & detail lowongan |
| `resources/views/partials/*` | `navbar`, `footer`, `brand-mark`, `tailwind` |
| `resources/views/layouts/public.blade.php` | Layout publik |
| `resources/views/company/profile/index.blade.php` | Form profil perusahaan |
| `resources/views/company/partnership/index.blade.php` | Daftar pengajuan kerja sama |
| `public/css/tokens.css`, `home.css`, `pages.css` | Token desain + gaya publik |
| `public/images/logo.png`, `public/images/team-meeting.jpg`, `public/images/team/*.jpg` | Aset visual |

## Route

| Method | URI | Nama |
|---|---|---|
| GET | `/` | `home` |
| GET | `/about` | `about` |
| GET | `/services` | `services` |
| GET | `/contact` | `contact` |
| POST | `/contact/partnership` | `contact.partnership` |
| GET | `/careers` | `careers.index` |
| GET | `/careers/{job}` | `careers.show` |
| GET | `/company/profile` | `company.profile.edit` |
| PUT | `/company/profile` | `company.profile.update` |
| GET | `/company/partnership` | `company.partnership.index` |
| PATCH | `/company/partnership/{partnership}/status` | `company.partnership.status.update` |

## Test terkait

- `tests/Feature/CareerApplicationTest.php` — katalog, pencarian/filter, detail lowongan
- `tests/Feature/SecurityAndAccessTest.php` — filter & profil perusahaan
- `tests/Feature/BusinessModulesTest.php` — modul kerja sama
- `tests/Feature/FullRouteSmokeTest.php` — seluruh halaman publik 200

## Checklist tugas

- [ ] Semua halaman publik menampilkan nama & identitas perusahaan yang sama
- [ ] Katalog hanya menampilkan lowongan aktif dan belum kedaluwarsa
- [ ] Pencarian & filter (kategori/lokasi/tipe/urutan) berfungsi + tombol Reset
- [ ] Formulir kerja sama tersimpan dan tampil di panel admin
- [ ] Unggah logo berfungsi (png/jpg/jpeg/webp/svg, maks 2 MB, butuh `storage:link`)
- [ ] Logo berlapis tiga tingkat: unggahan → `public/images/logo.*` → monogram "MB"
- [ ] Halaman responsif; semua warna/ukuran berasal dari `tokens.css` (tidak ada `:root` duplikat)
- [ ] Tidak ada tautan atau aset yang rusak (uji sapuan tautan)

## Koordinasi

- Kebutuhan token/warna baru diputuskan di sini, lalu dipakai bagian lain — **jangan** menulis ulang variabel di CSS lain.
- Perubahan pada `CareerController` bagian katalog dibarengi pemberitahuan ke Bagian 4.
