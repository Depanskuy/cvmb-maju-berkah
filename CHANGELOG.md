# Changelog

Semua perubahan penting pada Portal Karier CV Maju Berkah dicatat di berkas ini.
Format mengikuti [Keep a Changelog](https://keepachangelog.com/id/1.1.0/) dan
[Semantic Versioning](https://semver.org/lang/id/).

## [Belum Dirilis]

### Ditambahkan
- `tests/Feature/StatusTransitionTest.php` — pengunci kontrak transisi status lamaran,
  kerja sama, feedback, dan project, plus batas nilai gaji.

### Diubah
- **Kontrak transisi status bersifat final.** Lamaran `accepted`/`rejected` tidak dapat
  dikembalikan ke `processing`/`pending` maupun ditukar; kerja sama (`pending → approved → archived`),
  feedback (`Baru → Ditindaklanjuti`), dan project (`planning → ongoing → completed`) kini satu arah.
  Kontrak ditaruh di model (`ALLOWED_TRANSITIONS` + `canTransitionTo()`), ditegakkan controller,
  dan UI hanya menampilkan status tujuan yang sah.
- **Validasi deadline lowongan saat edit bersifat kondisional.** Nilai lama boleh dipertahankan
  (mis. lowongan lama yang tinggal diedit bagian lain), tetapi bila diubah wajib setelah hari ini.
- **Project berstatus "Selesai" otomatis berprogres 100%** agar tidak ada data "Selesai tetapi 60%".
- **Dashboard admin** menghitung "Lowongan Aktif" hanya yang benar-benar terbuka untuk publik
  (status aktif **dan** deadline belum lewat), dengan penanda jumlah lowongan kedaluwarsa.
- `.env.example` memakai `APP_DEBUG=false` untuk demo (`phpunit.xml` tetap memaksa `true` untuk test).

### Diperbaiki
- **Error 500 saat gaji di luar kapasitas kolom** (`decimal(12,2)`). Input seperti
  `salary_max = 299.991.100.000` kini ditolak sebagai kesalahan validasi
  (batas `Job::SALARY_MAX_ALLOWED` = 9.999.999.999), bukan halaman 500.
- Status lamaran yang sudah diputus tidak lagi dapat diubah kembali dari halaman review admin.

## [1.0.0] — 17 Oktober 2026

Rilis pertama sesuai `PROJECT_BLUEPRINT_CV_MAJU_BERKAH.md`.

### Ditambahkan
- Portal publik: beranda, tentang, layanan, karier (katalog + detail), kontak/pengajuan kerja sama.
- Autentikasi dua peran: Admin Perusahaan (`company_admin`) dan Pencari Kerja (`job_seeker`).
- Dashboard admin: ringkasan, CRUD lowongan, tinjauan lamaran, profil perusahaan,
  kerja sama, project, feedback, dan log aktivitas.
- Dashboard pelamar: lamaran saya, profil/berkas, serta ubah/tarik lamaran selama `pending`.
- Unggahan logo perusahaan dan foto tim; unduhan CV pelamar.
- Modul tambahan di luar blueprint: Activity Log, Partnership, Project, dan Feedback.

### Catatan
- Tema terang dengan palet biru `#3B82F6` (menyesuaikan panduan desain terbaru; deviasi
  terdokumentasi dari blueprint §11.1).
- Dua deviasi disengaja didokumentasikan di `docs/LAPORAN_IMPLEMENTASI.md` §5:
  detail lowongan `closed`/kedaluwarsa tetap dapat dibuka (BR-10) dan halaman 422 tidak dibuat.
