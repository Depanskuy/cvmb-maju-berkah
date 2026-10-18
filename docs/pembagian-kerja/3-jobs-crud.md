# Bagian 3 — CRUD Lowongan Admin

| | |
|---|---|
| **Branch** | `feature/3-jobs-crud` |
| **Peran blueprint §13** | **C** (CRUD Lowongan Admin) |
| **Jumlah route** | 13 dari 57 |

## Ruang lingkup

1. **CRUD lowongan kerja** — daftar, buat, detail, ubah, hapus/tutup, dan buka/tutup cepat.
2. **Validasi lowongan** — `salary_max >= salary_min`, deadline harus setelah hari ini, dsb.
3. **Dashboard ringkasan admin** — statistik lowongan & lamaran.
4. **Aturan bisnis BR-09** — lowongan yang **sudah punya lamaran tidak boleh dihapus**, melainkan
   ditutup (status `closed`).
5. **Modul project/portofolio** — CRUD proyek (klien, kategori, anggaran, jadwal, progres, status).
6. **Pembatasan kepemilikan** — admin hanya boleh mengelola lowongan **milik perusahaannya** (403 bila bukan).

## Berkas yang dipegang

| Berkas | Keterangan |
|---|---|
| `app/Http/Controllers/Admin/JobController.php` | CRUD lowongan + toggle status + aturan BR-09 |
| `app/Http/Controllers/Admin/DashboardController.php` | Dashboard ringkasan admin |
| `app/Http/Controllers/Admin/ProjectController.php` | CRUD project |
| `app/Models/Job.php` | Model lowongan *(schema dikelola bersama Bagian 1/2/4)* |
| `app/Models/Project.php` | Model project |
| `resources/views/company/jobs/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php` | Halaman CRUD lowongan |
| `resources/views/company/dashboard.blade.php` | Dashboard admin |
| `resources/views/company/project/index.blade.php` | Halaman project |
| `resources/views/layouts/company.blade.php` | Layout panel admin |
| `resources/views/components/sidebar.blade.php`, `topbar.blade.php` | Komponen panel admin |

## Route

| Method | URI | Nama |
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
| GET | `/company/project` | `company.project.index` |
| POST | `/company/project` | `company.project.store` |
| PUT | `/company/project/{project}` | `company.project.update` |
| DELETE | `/company/project/{project}` | `company.project.destroy` |

## Test terkait

- `tests/Feature/BusinessModulesTest.php` — modul project
- `tests/Feature/SecurityAndAccessTest.php` — batas kepemilikan lowongan (403)
- `tests/Feature/FullRouteSmokeTest.php` — seluruh route admin

## Checklist tugas

- [ ] Admin dapat membuat, melihat, mengubah, dan menutup/menghapus lowongan
- [ ] Input tidak valid ditolak (`salary_max >= salary_min`, deadline masa depan)
- [ ] Admin **tidak** dapat mengubah lowongan di luar kewenangannya (403)
- [ ] BR-09: lowongan berlamaran → ditutup, bukan dihapus
- [ ] Toggle buka/tutup status berfungsi
- [ ] Dashboard menampilkan ringkasan yang benar
- [ ] Perubahan lowongan langsung terlihat di katalog publik & portal pelamar

## Koordinasi

- Mengubah kolom/`status` lowongan **wajib** koordinasi dengan Bagian 1 (kontrak) dan Bagian 2/4 (pemakai).
- `app/Models/Job.php` dipakai bersama — hindari edit bersamaan.
