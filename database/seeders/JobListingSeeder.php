<?php

namespace Database\Seeders;

use App\Models\JobListing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title'           => 'Senior Financial Consultant',
                'category'        => 'Financial Consulting',
                'location'        => 'SCBD, Jakarta (Hybrid)',
                'employment_type' => 'full_time',
                'salary_min'      => 18000000,
                'salary_max'      => 25000000,
                'deadline'        => now()->addDays(30)->toDateString(),
                'status'          => 'active',
                'description'     => 'Sebagai Senior Financial Consultant di CV Maju Sukses, Anda akan memegang peran strategis dalam menuntun klien-klien korporat berskala enterprise melalui dinamika restrukturisasi finansial, evaluasi kelayakan investasi (due diligence), pemodelan proyeksi, serta valuasi Merger & Akuisisi (M&A). Anda akan bekerja berdampingan dengan para Managing Partners dan memimpin tim analis junior dalam merumuskan rekomendasi fiskal yang berdampak langsung pada kelangsungan bisnis konglomerasi, emiten terbuka di BEI, hingga BUMN strategis di Indonesia.',
                'requirements'    => "Pendidikan:\n- S1/S2 Keuangan, Akuntansi, Manajemen Bisnis, atau Ekonomi dari universitas terkemuka (IPK min. 3.40)\n\nPengalaman:\n- Minimal 3-5 tahun di corporate finance, boutique investment bank, atau Big-4 Advisory consulting\n\nSertifikasi (diutamakan):\n- CFA (Charterholder/Level II), CVA, WMI, atau CPA\n\nKemahiran Teknis:\n- Mahir dalam DCF, LBO, Comparable Multiples\n- Familiar dengan Bloomberg Terminal/Capital IQ\n- Menguasai PowerBI / Tableau\n\nKomunikasi:\n- Fasih berbahasa Inggris bisnis (lisan dan penulisan memo/laporan formal)\n\nRegulasi:\n- Pemahaman komprehensif atas POJK aksi korporasi pasar modal dan peraturan perbankan nasional",
            ],
            [
                'title'           => 'Business Transformation Specialist',
                'category'        => 'Business Strategy',
                'location'        => 'Jakarta Pusat (On-site)',
                'employment_type' => 'full_time',
                'salary_min'      => 15000000,
                'salary_max'      => 22000000,
                'deadline'        => now()->addDays(25)->toDateString(),
                'status'          => 'active',
                'description'     => 'Bertanggung jawab dalam mendiagnosis kemacetan alur kerja operasional, merancang blueprint rekayasa ulang proses bisnis (Business Process Re-engineering), dan mengawal integrasi rantai pasok manufaktur multinasional. Anda akan bekerja lintas fungsi dengan tim operasional, keuangan, dan teknologi untuk memastikan transformasi berjalan sesuai target.',
                'requirements'    => "Pendidikan:\n- S1/S2 Teknik Industri, Manajemen Operasional, atau bidang terkait\n\nPengalaman:\n- Minimal 4 tahun di bidang business process improvement atau management consulting\n\nKemampuan:\n- Mahir dalam process mapping (BPMN, Visio)\n- Familiar dengan Lean/Six Sigma\n- Kuat dalam change management\n\nSoft skill:\n- Kemampuan analisis dan problem solving yang kuat\n- Komunikasi lintas level organisasi\n- Bersedia melakukan perjalanan dinas ke lokasi klien",
            ],
            [
                'title'           => 'Digital Enterprise Architect',
                'category'        => 'Digital Transformation',
                'location'        => 'SCBD, Jakarta (Hybrid)',
                'employment_type' => 'full_time',
                'salary_min'      => 22000000,
                'salary_max'      => 35000000,
                'deadline'        => now()->addDays(45)->toDateString(),
                'status'          => 'active',
                'description'     => 'Membantu klien korporasi mendesain ekosistem arsitektur IT cloud berskala besar, memodernisasi legacy software, dan mengimplementasi integrasi enterprise ERP. Anda akan berperan sebagai penasihat teknis utama bagi CTO dan Head of Digital klien, serta mengawal roadmap transformasi digital multi-tahun.',
                'requirements'    => "Pendidikan:\n- S1/S2 Teknik Informatika, Sistem Informasi, atau bidang terkait\n\nPengalaman:\n- Minimal 5 tahun di bidang enterprise architecture atau solution architecture\n\nKemampuan Teknis:\n- Cloud Architecture (AWS / Azure / GCP)\n- Microservices & API Gateway\n- ERP (SAP, Oracle, Microsoft Dynamics)\n- Data integration & middleware\n\nSertifikasi (diutamakan):\n- TOGAF, AWS Solutions Architect, atau Azure Solutions Architect\n\nSoft skill:\n- Kemampuan presentasi ke level eksekutif\n- Kepemimpinan teknis lintas tim",
            ],
            [
                'title'           => 'Risk Management & Compliance Associate',
                'category'        => 'Risk & Governance',
                'location'        => 'SCBD, Jakarta',
                'employment_type' => 'full_time',
                'salary_min'      => 9000000,
                'salary_max'      => 14000000,
                'deadline'        => now()->addDays(20)->toDateString(),
                'status'          => 'active',
                'description'     => 'Menyusun kajian kepatuhan regulasi OJK & perbankan, mengembangkan matriks mitigasi risiko operasional, dan menerapkan standar Good Corporate Governance (GCG) untuk klien-klien lembaga keuangan dan korporasi terbuka.',
                'requirements'    => "Pendidikan:\n- S1 Akuntansi, Manajemen, Hukum Bisnis, atau bidang terkait\n\nPengalaman:\n- 2-4 tahun di bidang risk management, compliance, atau internal audit\n- Pengalaman di lembaga keuangan / konsultan (diutamakan)\n\nKemampuan:\n- Pemahaman regulasi OJK & BI\n- Familiar dengan framework COSO, ISO 31000\n- Analisis risiko kuantitatif dan kualitatif\n\nSoft skill:\n- Teliti, detail, dan mampu bekerja dengan deadline ketat\n- Kemampuan dokumentasi yang baik",
            ],
            [
                'title'           => 'Junior Strategy & Research Analyst',
                'category'        => 'Business Strategy',
                'location'        => 'Surabaya / Hybrid',
                'employment_type' => 'internship',
                'salary_min'      => 3500000,
                'salary_max'      => 5000000,
                'deadline'        => now()->addDays(15)->toDateString(),
                'status'          => 'active',
                'description'     => 'Program untuk fresh graduate atau mahasiswa tingkat akhir yang ingin memulai karier di bidang strategy consulting. Anda akan melakukan riset lanskap industri komparatif, mengumpulkan data primer pasar, menyusun presentasi eksekutif, dan mendukung tim senior dalam due diligence berkala.',
                'requirements'    => "Pendidikan:\n- Mahasiswa tingkat akhir atau fresh graduate S1 dari jurusan Manajemen, Ekonomi, Teknik Industri, atau terkait\n- IPK minimal 3.25\n\nKemampuan:\n- Mahir Microsoft Excel dan PowerPoint\n- Kemampuan riset dan analisis data\n- Familiar dengan Google Workspace\n\nSoft skill:\n- Rasa ingin tahu yang tinggi\n- Keterampilan komunikasi tertulis yang baik\n- Mampu bekerja dalam tim\n\nDurasi:\n- Program magang minimal 3 bulan, dengan peluang konversi ke posisi full-time",
            ],
            [
                'title'           => 'Corporate Legal Advisor',
                'category'        => 'Legal & Compliance',
                'location'        => 'SCBD, Jakarta (Hybrid)',
                'employment_type' => 'full_time',
                'salary_min'      => 16000000,
                'salary_max'      => 24000000,
                'deadline'        => now()->subDays(7)->toDateString(),
                'status'          => 'closed',
                'description'     => 'Memberikan nasihat hukum korporasi, meninjau kontrak komersial, dan mendampingi klien dalam transaksi M&A serta aksi korporasi lainnya. Posisi ini sudah ditutup karena telah terisi.',
                'requirements'    => "Pendidikan:\n- S1 Hukum dari universitas terkemuka\n\nPengalaman:\n- Minimal 5 tahun di corporate legal, law firm, atau in-house legal\n\nSertifikasi:\n- Memiliki izin praktik advokat (diutamakan)\n\nKemampuan:\n- Drafting & review kontrak komersial\n- Pemahaman hukum pasar modal\n- Kemampuan negosiasi yang kuat",
            ],
        ];

        foreach ($jobs as $job) {
            JobListing::updateOrCreate(
                ['slug' => Str::slug($job['title'])],
                array_merge($job, [
                    'slug' => Str::slug($job['title']),
                ])
            );
        }

        $this->command->info('✅ ' . count($jobs) . ' job listings seeded.');
    }
}