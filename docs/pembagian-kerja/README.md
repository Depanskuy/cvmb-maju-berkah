# Pembagian Kerja Tim — 4 Branch

Dokumen ini membagi pekerjaan **Portal Karier CV Maju Berkah** menjadi **4 bagian adil**.
Masing-masing bagian punya **branch Git sendiri**, dan semua branch diletakkan di dalam
folder `feature/` pada namespace Git sehingga benar-benar berupa satu folder berisi 4 branch.

Setiap branch sudah berisi **aplikasi utuh yang jalan** (bukan potongan yang rusak), jadi
anggota tinggal `git checkout` branch-nya lalu mulai bekerja pada bagiannya.

---

## Daftar branch

| # | Branch | Bagian | Peran blueprint §13 | Route |
|---|---|---|---|---|
| 1 | `feature/1-auth-dan-integrasi` | Autentikasi, Integrasi & Dokumentasi | **A + E** | 12 |
| 2 | `feature/2-company-profile` | Company Profile & UI Publik | **B** | 11 |
| 3 | `feature/3-jobs-crud` | CRUD Lowongan Admin | **C** | 13 |
| 4 | `feature/4-applications` | Lamaran & Dashboard Pencari Kerja | **D** | 21 |

**57 route aplikasi terbagi habis tanpa tumpang tindih** (12 + 11 + 13 + 21 = 57).

> Blueprint §13 menyarankan 5 peran. Karena tim dibagi **4**, **Peran A (Koordinator/Integrasi)**
> digabung dengan **Peran E (Integrasi Rekrutmen, QA & Dokumentasi)** — persis seperti yang
> disarankan blueprint: *"Jika anggota kurang dari lima orang: gabungkan Peran A dengan E."*
> Penggabungan ini juga membuat beban tiap bagian seimbang, karena bagian 4 memegang paling
> banyak route (21) sementara bagian 1 lebih banyak memegang tanggung jawab koordinasi.

| Bagian | Peran | Cakupan inti | Beban |
|---|---|---|---|
| 1 | A + E | Auth, migration/schema, config, seeder, daftar pelamar admin, log audit, QA, dokumentasi | Koordinasi + lintas-area |
| 2 | B | Halaman publik, layout/komponen bersama, katalog & detail lowongan, design token, aset | Tampilan & konten publik |
| 3 | C | CRUD lowongan admin, dashboard admin, modul project | CRUD admin |
| 4 | D | Profil pelamar, upload CV, CRUD lamaran, pencegahan lamaran ganda, dashboard pelamar, feedback | Alur pelamar (terbanyak route) |

---

## Cara pakai

```bash
git fetch --all
git branch -a                     # melihat 4 branch di dalam "folder" feature/
git checkout feature/3-jobs-crud  # contoh: mengambil bagian kamu
```

Setiap branch berisi berkas penanda `docs/pembagian-kerja/branch/<nama-branch>.md` yang
memuat ruang lingkup, daftar berkas yang dipegang, dan checklist tugas. **Baca berkas itu dulu.**

## Rincian tiap bagian

- [Bagian 1 — Autentikasi, Integrasi & Dokumentasi](1-auth-dan-integrasi.md)
- [Bagian 2 — Company Profile & UI Publik](2-company-profile.md)
- [Bagian 3 — CRUD Lowongan Admin](3-jobs-crud.md)
- [Bagian 4 — Lamaran & Dashboard Pencari Kerja](4-applications.md)

---

## Titik singgung antar bagian (wajib koordinasi)

| Berkas / area | Bagian | Kesepakatan |
|---|---|---|
| `app/Http/Controllers/Public/CareerController.php` | 2 & 4 | Bagian 2 memegang katalog & detail; bagian 4 memegang `apply`/`store` (kirim lamaran) |
| `app/Models/Job.php` | 2, 3, 4 | Schema lowongan dipakai bersama; **jangan ubah kolom tanpa koordinasi** |
| `routes/web.php` | semua | Satu berkas untuk semua; tambah route hanya di blok area sendiri |
| `database/migrations/` | 1 | Bagian 1 penjaga kontrak schema; bagian lain mengajukan migration baru lewat koordinasi |
| `public/css/tokens.css` | 2 | Sumber tunggal token desain; kebutuhan token baru minta ke bagian 2 |
| `app/Models/Company.php` | 2 & 3 | Profil perusahaan (bagian 2) dipakai untuk membatasi data admin (bagian 3) |

## Aturan kerja paralel (blueprint §13)

- Jangan mengedit berkas yang sama bersamaan tanpa koordinasi.
- Jangan membuat migration duplikat untuk tabel yang sama.
- Sepakati nama branch dan pull request sebelum coding.
- Setiap anggota membuat commit kecil dengan pesan yang jelas.
- Merge hanya setelah fitur diuji dan tidak merusak fitur lain.
- Jika menemukan kebutuhan perubahan, ajukan ke tim, sepakati, perbarui dokumen, lalu implementasikan.

## Aturan Git (blueprint §14)

- Jangan commit `.env`, kredensial, password, token, atau CV pribadi.
- Jangan `push --force` ke branch bersama kecuali disepakati dan dipahami semua anggota.
- Jangan mengubah migration yang sudah dipakai anggota lain — buat migration baru.
- Pull perubahan terbaru sebelum mulai bekerja; uji aplikasi sebelum membuka pull request.

---

## Kondisi awal (basis semua branch)

Basis keempat branch adalah kode yang sudah berjalan:

- **77 test lulus / 413 assertion** (`php artisan test`)
- Seluruh **60 route** tersapu tanpa error 500
- Aturan **status final** (lamaran `accepted`/`rejected` tidak dapat diubah lagi; kerja sama,
  feedback, dan project satu arah) serta **batas nilai gaji** (maks. 9.999.999.999) sudah aktif
  sejak basis ini — jangan dibuat ulang di branch masing-masing.
- PostgreSQL 18.6 · Laravel 12.69.3 · PHP 8.5.9
- Detail lengkap: [`docs/LAPORAN_IMPLEMENTASI.md`](../LAPORAN_IMPLEMENTASI.md) dan [`README.md`](../../README.md)
