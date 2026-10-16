@extends('layouts.public')

@section('title', 'Layanan Kami — CV Maju Sukses')

@section('content')

{{-- HERO --}}
<section class="page-hero">
    <div class="page-hero__glow-1"></div>
    <div class="page-hero__glow-2"></div>

    <div class="container-page page-hero__inner">
        <nav class="page-hero__breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="page-hero__breadcrumb-sep">/</span>
            <span class="page-hero__breadcrumb-current">Layanan Kami</span>
        </nav>

        <div class="page-hero__pill">
            <span class="page-hero__pill-dot"></span>
            <span>Solusi End-to-End Korporasi</span>
        </div>

        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; align-items:end; width:100%;" class="services-hero-grid">
            <div>
                <h1 class="page-hero__title">
                    Layanan Konsultasi Strategis Terpadu Berstandar Global
                </h1>
                <p class="page-hero__subtitle" style="margin-top:1rem;">
                    Dari penataan restrukturisasi modal dan kepatuhan regulasi institusional hingga deployment otomasi manufaktur skala enterprise—kami menghantarkan kepastian eksekusi bagi jajaran direksi di era disrupsi.
                </p>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem; padding:1rem; background:rgba(255,255,255,0.1); backdrop-filter:blur(12px); border-radius:0.75rem;">
                <div>
                    <div class="page-hero__metric-value">140+ Proyek</div>
                    <div class="page-hero__metric-label">Tuntas Diselesaikan</div>
                </div>
                <div style="height:1px; background:rgba(255,255,255,0.15);"></div>
                <div>
                    <div class="page-hero__metric-value" style="color:#fff;">98.4%</div>
                    <div class="page-hero__metric-label">Kepatuhan SLA &amp; Tata Kelola</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4 PILAR --}}
<section class="section-page section-page--bg-surface">
    <div class="container-page">
        <div style="display:flex; flex-direction:column; gap:1rem; margin-bottom:3rem;" class="services-heading-row">
            <div>
                <span class="section-tag-pill" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Katalog Solusi</span>
                <h2 class="section-title-lg section-title-lg--primary" style="margin-top:0.25rem;">4 Pilar Transformasi Korporasi</h2>
            </div>
            <p style="font-size:15px; line-height:1.7; color:var(--on-surface-variant); max-width:28rem; margin:0;">
                Integrasi multidisiplin dirancang khusus untuk entitas skala menengah hingga konglomerasi yang menuntut presisi matematis dan akuntabilitas yuridis.
            </p>
        </div>

        @php
            $services_list = [
                [
                    'icon' => 'finance_mode', 'badge' => 'Pilar 01',
                    'title' => 'Konsultasi Finansial, M&A & Restrukturisasi Modal',
                    'desc' => 'Arsitektur rekayasa finansial tingkat lanjut untuk mengoptimalkan struktur permodalan, valuasi aset riil, dan penyiapan sindikasi pembiayaan perbankan maupun private equity.',
                    'items' => [
                        'Valuasi Bisnis & Due Diligence Finansial Komprehensif',
                        'Studi Kelayakan Investasi & Model Proyeksi Arus Kas',
                        'Skema Sindikasi Pendanaan & Restrukturisasi Utang Korporat',
                    ],
                    'sectors' => ['Infrastruktur', 'Holding Korporasi', 'Properti & Real Estate'],
                    'duration' => '8 - 20 Minggu',
                ],
                [
                    'icon' => 'precision_manufacturing', 'badge' => 'Pilar 02 · Flagship',
                    'title' => 'Transformasi Digital, ERP & Otomasi Industri',
                    'desc' => 'Modernisasi rantai pasok manufaktur dan orkestrasi data operasional real-time melalui integrasi Enterprise Resource Planning (ERP), IoT sensorik lini produksi, dan dashboard analitik terpadu.',
                    'items' => [
                        'Audit Kesiapan Arsitektur TI & Roadmap Digital Industri 4.0',
                        'Implementasi & Penyelarasan Sistem ERP (SAP/Oracle/Odoo)',
                        'Command Center Telemetri Logistik & Pemeliharaan Prediktif',
                    ],
                    'duration' => '12 - 36 Minggu',
                ],
                [
                    'icon' => 'gavel', 'badge' => 'Pilar 03',
                    'title' => 'Tata Kelola Perusahaan, Legal & Kepatuhan Risiko',
                    'desc' => 'Membangun benteng pertahanan korporasi melalui penegakan Good Corporate Governance (GCG), mitigasi risiko kepatuhan hukum perizinan, dan kepatuhan standar audit perbankan serta OJK.',
                    'items' => [
                        'Penyusunan Pedoman GCG, Board Charter & Standar Kode Etik',
                        'Compliance Review Regulasi Sektoral, Perpajakan & Perizinan OSS',
                        'Sistem Manajemen Risiko Enterprise (ISO 31000) & Whistleblowing',
                    ],
                    'sectors' => ['Jasa Keuangan', 'Energi & Tambang', 'FMCG & Ritel'],
                    'duration' => '6 - 16 Minggu',
                ],
                [
                    'icon' => 'hub', 'badge' => 'Pilar 04',
                    'title' => 'Manajemen Strategis, Organisasi & Efisiensi Operasional',
                    'desc' => 'Restrukturisasi model bisnis, efisiensi rantai pasok dengan metodologi Lean Six Sigma, penataan matriks akuntabilitas organisasi (RACI), dan program change management menyeluruh.',
                    'items' => [
                        'Redesain Struktur Organisasi & Standarisasi KPI/OKR Korporat',
                        'Business Process Re-engineering (BPR) & Eliminasi Waste',
                        'Manajemen Perubahan & Pelatihan Kepemimpinan Eksekutif',
                    ],
                    'sectors' => ['Logistik & Distribusi', 'Healthcare', 'Manufaktur Berat'],
                    'duration' => '10 - 24 Minggu',
                ],
            ];
        @endphp

        <div class="services-pillars">
            @foreach ($services_list as $svc)
                <div class="service-pillar">
                    <div>
                        <div class="service-pillar__head">
                            <div class="service-pillar__icon">
                                <span class="material-symbols-outlined">{{ $svc['icon'] }}</span>
                            </div>
                            <span class="service-pillar__badge {{ $svc['badge'] === 'Pilar 02 · Flagship' ? 'service-pillar__badge--featured' : '' }}">
                                {{ $svc['badge'] }}
                            </span>
                        </div>
                        <h3 class="service-pillar__title">{{ $svc['title'] }}</h3>
                        <p class="service-pillar__desc">{{ $svc['desc'] }}</p>

                        <span class="service-pillar__deliverables-label">Cakupan Deliverable Utama:</span>
                        <ul class="service-pillar__deliverables">
                            @foreach ($svc['items'] as $item)
                                <li>
                                    <span class="material-symbols-outlined">check_circle</span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>

                        @if (!empty($svc['sectors']))
                            <span class="service-pillar__sectors-label">Fokus Sektor:</span>
                            <div class="service-pillar__sectors">
                                @foreach ($svc['sectors'] as $sector)
                                    <span class="service-pillar__sector-tag">{{ $sector }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="service-pillar__footer">
                        <span class="service-pillar__duration">Durasi: {{ $svc['duration'] }}</span>
                        <a class="service-pillar__cta" href="{{ route('contact') }}">
                            Konsultasikan Layanan Ini
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- METODOLOGI --}}
<section class="section-page section-page--bg-surface-low">
    <div class="container-page">

        <div class="section-heading">
            <span class="section-tag-pill section-tag-pill--gold" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Kerangka Kerja Teruji</span>
            <h2 class="section-title-lg section-title-lg--primary">Metodologi 4 Tahap Transformasi</h2>
            <p class="section-sub-lg">
                Setiap keterlibatan konsultan berlandaskan parameter analitik yang ketat demi meminimalisasi friksi operasional dan menjamin ROI strategis.
            </p>
        </div>

        <div class="methodology-grid">
            @php
                $methods = [
                    ['n' => '01', 'icon' => 'troubleshoot',      'title' => 'Audit Diagnostik Mendalam',  'desc' => 'Investigasi menyeluruh terhadap data historis, kapabilitas neraca, sistem operasional, dan interview pemangku kepentingan kunci.', 'duration' => 'Minggu 1 - 3'],
                    ['n' => '02', 'icon' => 'schema',            'title' => 'Perumusan Roadmap & Solusi', 'desc' => 'Penyusunan blueprint arsitektural, kalkulasi mitigasi risiko, formulasi business case, dan penetapan KPI yang terukur presisi.',        'duration' => 'Minggu 4 - 6'],
                    ['n' => '03', 'icon' => 'engineering',       'title' => 'Pendampingan & Eksekusi',    'desc' => 'Konsultan senior terjun langsung mendampingi implementasi di lantai operasi pabrik, integrasi server TI, maupun ruang sidang direksi.', 'duration' => 'Fase Utama'],
                    ['n' => '04', 'icon' => 'workspace_premium', 'title' => 'Dampak & Transfer Ilmu',     'desc' => 'Audit pasca-implementasi, verifikasi capaian efisiensi finansial, penyusunan SOP institusional, dan sertifikasi tim internal klien.',  'duration' => 'Berkelanjutan'],
                ];
            @endphp

            @foreach ($methods as $m)
                <div class="method-card">
                    <div class="method-card__number">{{ $m['n'] }}</div>
                    <div>
                        <div class="method-card__icon">
                            <span class="material-symbols-outlined">{{ $m['icon'] }}</span>
                        </div>
                        <h4 class="method-card__title">{{ $m['title'] }}</h4>
                        <p class="method-card__desc">{{ $m['desc'] }}</p>
                    </div>
                    <div class="method-card__duration">
                        <span>{{ $m['duration'] }}</span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SKEMA KERJASAMA --}}
<section class="section-page section-page--bg-surface">
    <div class="container-page">

        <div class="section-heading">
            <span class="section-tag-pill" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Fleksibilitas Kemitraan</span>
            <h2 class="section-title-lg section-title-lg--primary">Skema Keterlibatan Konsultan</h2>
            <p class="section-sub-lg">Pilihan model kontrak kerja yang adaptif terhadap skala organisasi, kompleksitas regulasi, serta urgensi proyek korporasi Anda.</p>
        </div>

        <div class="engagement-grid">
            {{-- Model A --}}
            <div class="engagement-card">
                <div>
                    <div class="engagement-card__head">
                        <span class="engagement-card__opt">Opsi A</span>
                        <span class="material-symbols-outlined engagement-card__icon">assignment</span>
                    </div>
                    <h3 class="engagement-card__title">Project-Based Advisory</h3>
                    <p class="engagement-card__desc">Ideal untuk inisiatif spesifik dengan ruang lingkup dan jangka waktu terdefinisi ketat, seperti studi kelayakan investasi atau audit kepatuhan GCG.</p>
                    <ul class="engagement-card__features">
                        <li><span class="material-symbols-outlined">check</span> Output &amp; deliverable terikat SLA kontraktual</li>
                        <li><span class="material-symbols-outlined">check</span> Fixed budget tanpa biaya tersembunyi</li>
                        <li><span class="material-symbols-outlined">check</span> Alokasi tim spesialis sektoral dedicated</li>
                    </ul>
                </div>
                <a class="engagement-card__btn" href="{{ route('contact') }}">Pilih Skema Proyek</a>
            </div>

            {{-- Model B (Featured) --}}
            <div class="engagement-card engagement-card--featured" style="position:relative;">
                <span class="engagement-card__ribbon">Paling Direkomendasikan</span>
                <div>
                    <div class="engagement-card__head" style="margin-top:0.5rem;">
                        <span class="engagement-card__opt">Opsi B</span>
                        <span class="material-symbols-outlined engagement-card__icon">stars</span>
                    </div>
                    <h3 class="engagement-card__title">Retainer Strategic Partner</h3>
                    <p class="engagement-card__desc">Kemitraan berkesinambungan mendampingi jajaran Direksi, Komisaris, dan Kepala Divisi sebagai strategic counsel bulanan atau kuartalan.</p>
                    <ul class="engagement-card__features">
                        <li><span class="material-symbols-outlined">check_circle</span> Akses prioritas 24/7 konsultasi manajemen C-Level</li>
                        <li><span class="material-symbols-outlined">check_circle</span> Review berkala KPI &amp; pemantauan mitigasi risiko</li>
                        <li><span class="material-symbols-outlined">check_circle</span> Seat permanen pada rapat evaluasi strategis</li>
                    </ul>
                </div>
                <a class="engagement-card__btn" href="{{ route('contact') }}">Mulai Kemitraan Retainer</a>
            </div>

            {{-- Model C --}}
            <div class="engagement-card">
                <div>
                    <div class="engagement-card__head">
                        <span class="engagement-card__opt">Opsi C</span>
                        <span class="material-symbols-outlined engagement-card__icon">flash_on</span>
                    </div>
                    <h3 class="engagement-card__title">Specialized Task Force</h3>
                    <p class="engagement-card__desc">Pengerahan tim ad-hoc multi-bidang dengan akselerasi tinggi untuk menangani krisis operasional, restrukturisasi mendesak, atau target M&amp;A agresif.</p>
                    <ul class="engagement-card__features">
                        <li><span class="material-symbols-outlined">check</span> Mobilisasi kilat dalam tempo &lt; 72 jam</li>
                        <li><span class="material-symbols-outlined">check</span> Kombinasi pakar legal, keuangan &amp; IT forensik</li>
                        <li><span class="material-symbols-outlined">check</span> Pelaporan harian berbasis executive dashboard</li>
                    </ul>
                </div>
                <a class="engagement-card__btn" href="{{ route('contact') }}">Aktivasi Task Force</a>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="section-page section-page--bg-surface-low">
    <div class="container-page" style="max-width: 56rem;">

        <div class="section-heading">
            <span class="section-tag-pill" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Pertanyaan Sering Diajukan</span>
            <h2 class="section-title-lg section-title-lg--primary">Klarifikasi Prosedur Layanan</h2>
        </div>

        @php
            $faqs = [
                ['q' => 'Bagaimana CV Maju Sukses menjamin kerahasiaan data korporasi klien (NDA)?', 'a' => 'Setiap penugasan didahului penandatanganan Non-Disclosure Agreement (NDA) dua arah yang mengikat secara hukum dengan klausul sanksi ketat. Akses data sensitif dienkripsi secara digital dan hanya dibuka pada konsultan inti yang memegang sertifikasi kepatuhan data ISO/IEC 27001.'],
                ['q' => 'Berapa rata-rata durasi proyek konsultasi dari kickoff hingga go-live?', 'a' => 'Bergantung pada skala pilar yang dipilih. Proyek audit hukum atau tata kelola biasanya berkisar 6 hingga 12 minggu. Sementara proyek transformasi digital ERP pabrik atau restrukturisasi menyeluruh memerlukan waktu 16 hingga 36 minggu dengan pengawalan pasca-implementasi.'],
                ['q' => 'Apa kualifikasi dan latar belakang konsultan yang ditugaskan?', 'a' => 'Tim penasihat kami terdiri dari mantan praktisi C-suite, akuntan publik tersertifikasi (CPA/CFA), advokat hukum korporasi terdaftar, serta arsitek industri terakreditasi Lean Six Sigma Black Belt dengan pengalaman rata-rata di atas 12 tahun di sektor terkait.'],
                ['q' => 'Apakah ada program pendampingan lanjutan setelah proyek selesai diserahkan?', 'a' => 'Tentu. Kami menyediakan masa retensi audit paska-proyek (warranty support) selama 60 hingga 90 hari tanpa biaya tambahan, mencakup evaluasi stabilitas sistem baru serta bimbingan audit tindak lanjut bagi manajemen internal.'],
            ];
        @endphp

        <div class="faq-list">
            @foreach ($faqs as $i => $faq)
                <div class="faq-item">
                    <button class="faq-trigger" type="button" onclick="toggleFaq({{ $i }})" id="faq-trigger-{{ $i }}">
                        <span>{{ $faq['q'] }}</span>
                        <span class="material-symbols-outlined">expand_more</span>
                    </button>
                    <div class="faq-answer" id="faq-answer-{{ $i }}">
                        <div class="faq-answer__inner">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section-page section-page--bg-surface" style="padding: 3rem 0;">
    <div class="container-page">
        <div class="cta-block">
            <div class="cta-block__glow-1"></div>
            <div class="cta-block__glow-2"></div>
            <div class="cta-block__inner">
                <div class="cta-block__content">
                    <span class="cta-block__pill">Siap Mengakselerasi Daya Saing Organisasi?</span>
                    <h2 class="cta-block__title">Diskusikan Kebutuhan Strategis Bersama Konsultan Senior Kami</h2>
                    <p class="cta-block__desc">
                        Jadwalkan sesi confidential diagnostic assessment selama 45 menit tanpa komitmen awal untuk meninjau opsi solusi terbaik bagi perusahaan Anda.
                    </p>
                </div>
                <div class="cta-block__actions">
                    <a href="{{ route('contact') }}" class="btn-page btn-page--primary">
                        Konsultasi Kebutuhan Anda
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <button class="btn-page btn-page--outline-white" onclick="alert('Katalog Layanan CV Maju Sukses (PDF) sedang diunduh.');" type="button">
                        <span class="material-symbols-outlined">download</span>
                        Unduh Katalog (PDF)
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    @media (min-width: 1024px) {
        .services-hero-grid { grid-template-columns: 8fr 4fr; }
        .services-heading-row { flex-direction: row; align-items: end; justify-content: space-between; }
    }
</style>
@endpush

@push('scripts')
<script>
function toggleFaq(i) {
    const answer = document.getElementById('faq-answer-' + i);
    const trigger = document.getElementById('faq-trigger-' + i);
    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

    // Close all
    document.querySelectorAll('.faq-answer').forEach(el => el.style.maxHeight = '0px');
    document.querySelectorAll('.faq-trigger').forEach(el => el.classList.remove('is-open'));

    // Open the selected one if it wasn't open
    if (!isOpen) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        trigger.classList.add('is-open');
    }
}
</script>
@endpush