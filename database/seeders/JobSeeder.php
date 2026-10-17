<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = Company::query()->value('id');
        $company   = 'CV Maju Berkah';

        $jobs = [
            [
                'title'           => 'Supervisor Gudang & Persediaan',
                'category'        => 'Gudang & Persediaan',
                'location'        => 'Jakarta Selatan (On-site)',
                'employment_type' => 'full_time',
                'salary_min'      => 8000000,
                'salary_max'      => 12000000,
                'deadline'        => now()->addDays(30)->toDateString(),
                'status'          => 'active',
                'description'     => "Sebagai Supervisor Gudang & Persediaan di {$company}, Anda memastikan seluruh stok barang masuk dan keluar tercatat akurat, tersusun rapi, dan siap dikirim tepat waktu ke toko, UMKM, maupun mitra bisnis. Anda memimpin tim gudang harian dan menjaga akurasi data persediaan di sistem.",
                'requirements'    => "Pendidikan:\n- Minimal D3/S1 Manajemen, Logistik, atau Teknik Industri\n\nPengalaman:\n- Minimal 2 tahun menangani gudang atau persediaan di perusahaan distribusi/retail\n\nKemampuan:\n- Memahami FIFO, stock opname, dan pengendalian stok\n- Terbiasa menggunakan sistem inventori (ERP/spreadsheet lanjutan)\n- Mampu memimpin tim 5-10 orang\n\nSoft skill:\n- Teliti, disiplin, dan kuat dalam mengatur prioritas\n- Komunikasi yang baik dengan tim pengiriman dan penjualan",
            ],
            [
                'title'           => 'Sales Lapangan (Area Jakarta)',
                'category'        => 'Penjualan & Pemasaran',
                'location'        => 'Jakarta & sekitarnya (Mobile)',
                'employment_type' => 'full_time',
                'salary_min'      => 6000000,
                'salary_max'      => 9500000,
                'deadline'        => now()->addDays(25)->toDateString(),
                'status'          => 'active',
                'description'     => "Anda akan mengunjungi toko, warung, dan UMKM mitra untuk menawarkan produk kebutuhan usaha {$company}, membuka pelanggan baru, dan menjaga hubungan dengan pelanggan lama. Target utama Anda adalah pertumbuhan penjualan area yang konsisten.",
                'requirements'    => "Pendidikan:\n- Minimal SMA/SMK sederajat, diutamakan D3/S1\n\nPengalaman:\n- Minimal 1 tahun sebagai sales lapangan/canvasser (fresh graduate dipersilakan)\n\nKemampuan:\n- Mahir negosiasi dan presentasi produk\n- Memiliki kendaraan bermotor dan SIM C\n- Terbiasa bekerja dengan target penjualan\n\nSoft skill:\n- Ramah, ulet, dan jujur\n- Disiplin dalam pelaporan harian kunjungan",
            ],
            [
                'title'           => 'Staf Administrasi Distribusi',
                'category'        => 'Administrasi & Operasional',
                'location'        => 'Jakarta Selatan (On-site)',
                'employment_type' => 'full_time',
                'salary_min'      => 4500000,
                'salary_max'      => 6500000,
                'deadline'        => now()->addDays(20)->toDateString(),
                'status'          => 'active',
                'description'     => "Posisi ini menangani dokumen pengiriman, faktur, dan surat jalan, serta menjadi jembatan administrasi antara tim gudang, tim penjualan, dan pelanggan {$company}.",
                'requirements'    => "Pendidikan:\n- Minimal D3 Administrasi, Manajemen, atau Akuntansi\n\nPengalaman:\n- 1 tahun di posisi administrasi/operasional (diutamakan)\n\nKemampuan:\n- Mahir Microsoft Excel dan Google Workspace\n- Teliti dalam pengarsipan dokumen\n- Terbiasa membuat laporan harian\n\nSoft skill:\n- Rapi, disiplin, dan cepat belajar\n- Mampu bekerja multitugas dengan tenggat ketat",
            ],
            [
                'title'           => 'Staf Pengiriman & Logistik',
                'category'        => 'Logistik & Distribusi',
                'location'        => 'Jakarta Selatan (On-site)',
                'employment_type' => 'full_time',
                'salary_min'      => 4800000,
                'salary_max'      => 7000000,
                'deadline'        => now()->addDays(28)->toDateString(),
                'status'          => 'active',
                'description'     => "Mengatur jadwal pengiriman, memilih rute efisien, dan memastikan barang sampai ke pelanggan dalam kondisi baik dan tepat waktu. Anda juga berkoordinasi dengan mitra ekspedisi {$company}.",
                'requirements'    => "Pendidikan:\n- Minimal SMA/SMK sederajat\n\nPengalaman:\n- 1-2 tahun di bidang logistik, ekspedisi, atau pengiriman barang\n\nKemampuan:\n- Memahami alur pengiriman dan manajemen rute\n- Terbiasa dengan dokumen surat jalan\n- Mampu bekerja di lapangan\n\nSoft skill:\n- Bertanggung jawab dan tepat waktu\n- Komunikatif saat berhadapan dengan pelanggan",
            ],
            [
                'title'           => 'Admin E-Commerce & Marketplace',
                'category'        => 'Digital & E-Commerce',
                'location'        => 'Jakarta Selatan (Hybrid)',
                'employment_type' => 'full_time',
                'salary_min'      => 5000000,
                'salary_max'      => 7500000,
                'deadline'        => now()->addDays(35)->toDateString(),
                'status'          => 'active',
                'description'     => "Mengelola katalog produk {$company} di berbagai marketplace, memproses pesanan masuk, membalas pertanyaan pembeli, dan menyusun laporan penjualan daring untuk mendukung keputusan tim penjualan.",
                'requirements'    => "Pendidikan:\n- Minimal D3 semua jurusan\n\nPengalaman:\n- 1 tahun mengelola toko marketplace (Tokopedia/Shopee) diutamakan\n\nKemampuan:\n- Memahami dashboard seller marketplace\n- Mahir Excel/spreadsheet untuk laporan\n- Menguasai penulisan deskripsi produk yang menarik\n\nSoft skill:\n- Responsif terhadap pelanggan\n- Teliti dan konsisten",
            ],
            [
                'title'           => 'Magang Staf Operasional Distribusi',
                'category'        => 'Administrasi & Operasional',
                'location'        => 'Jakarta Selatan (On-site)',
                'employment_type' => 'internship',
                'salary_min'      => 2500000,
                'salary_max'      => 3500000,
                'deadline'        => now()->addDays(15)->toDateString(),
                'status'          => 'active',
                'description'     => "Program magang untuk mahasiswa tingkat akhir atau fresh graduate yang ingin mengenal langsung proses bisnis distribusi {$company}, mulai dari penerimaan barang, pencatatan persediaan, hingga pelayanan pelanggan.",
                'requirements'    => "Pendidikan:\n- Mahasiswa tingkat akhir atau fresh graduate D3/S1\n\nKemampuan:\n- Mahir Microsoft Excel dan Google Workspace\n- Mampu bekerja dalam tim\n\nSoft skill:\n- Rasa ingin tahu yang tinggi\n- Komunikasi tertulis yang baik\n\nDurasi:\n- Minimal 3 bulan, dengan peluang konversi ke posisi full-time",
            ],
            [
                'title'           => 'Staf Keuangan & Penagihan',
                'category'        => 'Keuangan',
                'location'        => 'Jakarta Selatan (On-site)',
                'employment_type' => 'full_time',
                'salary_min'      => 5500000,
                'salary_max'      => 8000000,
                'deadline'        => now()->subDays(7)->toDateString(),
                'status'          => 'closed',
                'description'     => "Menangani pencatatan transaksi penjualan, penagihan piutang mitra usaha, dan penyusunan laporan keuangan sederhana. Posisi ini sudah ditutup karena telah terisi.",
                'requirements'    => "Pendidikan:\n- Minimal D3/S1 Akuntansi atau Keuangan\n\nPengalaman:\n- 2 tahun di bidang keuangan/penagihan\n\nKemampuan:\n- Memahami dasar akuntansi dan pajak\n- Mahir Microsoft Excel\n\nSoft skill:\n- Teliti, jujur, dan bertanggung jawab",
            ],
        ];

        foreach ($jobs as $job) {
            Job::updateOrCreate(
                ['slug' => Str::slug($job['title'])],
                array_merge($job, [
                    'slug'       => Str::slug($job['title']),
                    'company_id' => $companyId,
                ])
            );
        }

        $this->command?->info('✅ ' . count($jobs) . ' lowongan (jobs) seeded.');
    }
}
