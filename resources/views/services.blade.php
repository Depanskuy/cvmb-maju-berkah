@extends('layouts.public')

@section('title', 'Layanan Kami — CV Maju Berkah')

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
            <span>Solusi Distribusi &amp; Pengadaan Terpadu</span>
        </div>

        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; align-items:end; width:100%;" class="services-hero-grid">
            <div>
                <h1 class="page-hero__title">
                    Layanan Distribusi Barang &amp; Penyediaan Kebutuhan Usaha
                </h1>
                <p class="page-hero__subtitle" style="margin-top:1rem;">
                    Dari pengadaan kebutuhan usaha sehari-hari hingga distribusi skala besar ke seluruh Indonesia—kami memastikan pasokan Anda tersedia tepat waktu, lengkap, dan dengan harga bersaing.
                </p>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem; padding:1rem; background:rgba(255,255,255,0.1); backdrop-filter:blur(12px); border-radius:0.75rem;">
                <div>
                    <div class="page-hero__metric-value">2.000+ Mitra</div>
                    <div class="page-hero__metric-label">Ritel, UMKM &amp; Institusi</div>
                </div>
                <div style="height:1px; background:rgba(255,255,255,0.15);"></div>
                <div>
                    <div class="page-hero__metric-value" style="color:#fff;">99%</div>
                    <div class="page-hero__metric-label">Ketepatan Pengiriman</div>
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
                <span class="section-tag-pill" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Katalog Layanan</span>
                <h2 class="section-title-lg section-title-lg--primary" style="margin-top:0.25rem;">4 Layanan Distribusi Utama</h2>
            </div>
            <p style="font-size:15px; line-height:1.7; color:var(--on-surface-variant); max-width:28rem; margin:0;">
                Dirancang untuk memenuhi kebutuhan pasokan barang dan kebutuhan usaha bagi ritel, UMKM, perkantoran, hingga institusi.
            </p>
        </div>

        @php
            $services_list = [
                [
                    'icon' => 'inventory_2', 'badge' => 'Layanan 01',
                    'title' => 'Distribusi Barang & Kebutuhan Usaha',
                    'desc' => 'Penyediaan dan penyaluran barang kebutuhan sehari-hari, perlengkapan kantor, serta kebutuhan operasional usaha dalam jumlah besar maupun eceran.',
                    'items' => [
                        'Pasokan rutin untuk ritel, toko, dan UMKM',
                        'Pengadaan perlengkapan kantor & kebutuhan operasional',
                        'Pengiriman terjadwal dengan armada yang andal',
                    ],
                    'sectors' => ['Ritel & Toko', 'UMKM', 'Perkantoran'],
                    'duration' => 'Harian - Mingguan',
                ],
                [
                    'icon' => 'local_shipping', 'badge' => 'Layanan 02 · Unggulan',
                    'title' => 'Logistik, Pergudangan & Pengiriman',
                    'desc' => 'Dukungan penyimpanan dan pengiriman yang andal melalui jaringan gudang dan armada untuk memastikan barang sampai dengan aman dan tepat waktu.',
                    'items' => [
                        'Penyimpanan barang di gudang berstandar',
                        'Pengiriman ke berbagai kota di Indonesia',
                        'Pemantauan status pesanan yang transparan',
                    ],
                    'sectors' => ['Distribusi', 'Manufaktur', 'Institusi'],
                    'duration' => 'Sesuai Jadwal',
                ],
                [
                    'icon' => 'shopping_cart', 'badge' => 'Layanan 03',
                    'title' => 'Pengadaan Massal & Kemitraan Pemasok',
                    'desc' => 'Pengadaan barang dalam jumlah besar dengan harga kompetitif melalui jaringan pemasok terpercaya dan terverifikasi.',
                    'items' => [
                        'Negosiasi harga & kontrak pasokan',
                        'Kurasi dan verifikasi pemasok',
                        'Pengadaan sesuai spesifikasi mitra',
                    ],
                    'sectors' => ['Swasta', 'BUMN', 'Instansi'],
                    'duration' => 'Per Kontrak',
                ],
                [
                    'icon' => 'support_agent', 'badge' => 'Layanan 04',
                    'title' => 'Layanan Pelanggan & Dukungan Mitra',
                    'desc' => 'Tim layanan yang responsif membantu mitra mulai dari pemesanan, penanganan keluhan, hingga pengaturan pengiriman ulang.',
                    'items' => [
                        'Pemesanan & konsultasi via telepon/WhatsApp',
                        'Penanganan keluhan dan retur barang',
                        'Pendampingan untuk mitra usaha baru',
                    ],
                    'sectors' => ['Semua Sektor'],
                    'duration' => 'Berkelanjutan',
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
                            <span class="service-pillar__badge {{ str_contains($svc['badge'], 'Unggulan') ? 'service-pillar__badge--featured' : '' }}">
                                {{ $svc['badge'] }}
                            </span>
                        </div>
                        <h3 class="service-pillar__title">{{ $svc['title'] }}</h3>
                        <p class="service-pillar__desc">{{ $svc['desc'] }}</p>

                        <span class="service-pillar__deliverables-label">Yang Anda Dapatkan:</span>
                        <ul class="service-pillar__deliverables">
                            @foreach ($svc['items'] as $item)
                                <li>
                                    <span class="material-symbols-outlined">check_circle</span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>

                        @if (!empty($svc['sectors']))
                            <span class="service-pillar__sectors-label">Cocok Untuk:</span>
                            <div class="service-pillar__sectors">
                                @foreach ($svc['sectors'] as $sector)
                                    <span class="service-pillar__sector-tag">{{ $sector }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="service-pillar__footer">
                        <span class="service-pillar__duration">Periode: {{ $svc['duration'] }}</span>
                        <a class="service-pillar__cta" href="{{ route('contact') }}">
                            Tanyakan Layanan Ini
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
            <span class="section-tag-pill section-tag-pill--gold" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Alur Kerja Kami</span>
            <h2 class="section-title-lg section-title-lg--primary">4 Tahap Layanan Distribusi</h2>
            <p class="section-sub-lg">
                Setiap pesanan diproses secara terencana agar barang tiba lengkap, dalam kondisi baik, dan tepat waktu.
            </p>
        </div>

        <div class="methodology-grid">
            @php
                $methods = [
                    ['n' => '01', 'icon' => 'edit_document',   'title' => 'Pemesanan & Konsultasi', 'desc' => 'Mitra menyampaikan kebutuhan barang melalui kontak, telepon, atau WhatsApp; tim kami mencatat spesifikasi dan jumlahnya.', 'duration' => 'Hari 1'],
                    ['n' => '02', 'icon' => 'fact_check',      'title' => 'Verifikasi & Pengadaan', 'desc' => 'Kami memverifikasi ketersediaan stok, melakukan pengadaan dari pemasok terpercaya, dan menyiapkan pesanan Anda.',       'duration' => 'Hari 1 - 3'],
                    ['n' => '03', 'icon' => 'local_shipping',  'title' => 'Penyiapan & Pengiriman', 'desc' => 'Barang dikemas dan dimuat ke armada setelah melewati pemeriksaan kualitas, lalu dikirim ke lokasi mitra.',            'duration' => 'Hari 2 - 5'],
                    ['n' => '04', 'icon' => 'handshake',       'title' => 'Serah Terima & Dukungan','desc' => 'Barang diterima mitra disertai bukti serah terima, dengan dukungan penanganan jika terdapat kendala.',                'duration' => 'Berkelanjutan'],
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
            <h2 class="section-title-lg section-title-lg--primary">Pilihan Model Kerja Sama</h2>
            <p class="section-sub-lg">Pilihan model pasokan yang dapat disesuaikan dengan skala usaha, kebutuhan barang, dan anggaran Anda.</p>
        </div>

        <div class="engagement-grid">
            {{-- Model A --}}
            <div class="engagement-card">
                <div>
                    <div class="engagement-card__head">
                        <span class="engagement-card__opt">Opsi A</span>
                        <span class="material-symbols-outlined engagement-card__icon">shopping_bag</span>
                    </div>
                    <h3 class="engagement-card__title">Pembelian Eceran &amp; Rutin</h3>
                    <p class="engagement-card__desc">Cocok untuk toko, UMKM, dan pelanggan yang membutuhkan pasokan barang dalam jumlah kecil namun rutin.</p>
                    <ul class="engagement-card__features">
                        <li><span class="material-symbols-outlined">check</span> Tanpa minimum pembelian besar</li>
                        <li><span class="material-symbols-outlined">check</span> Harga transparan &amp; kompetitif</li>
                        <li><span class="material-symbols-outlined">check</span> Pengiriman yang fleksibel</li>
                    </ul>
                </div>
                <a class="engagement-card__btn" href="{{ route('contact') }}">Mulai Berbelanja</a>
            </div>

            {{-- Model B (Featured) --}}
            <div class="engagement-card engagement-card--featured" style="position:relative;">
                <span class="engagement-card__ribbon">Paling Diminati</span>
                <div>
                    <div class="engagement-card__head" style="margin-top:0.5rem;">
                        <span class="engagement-card__opt">Opsi B</span>
                        <span class="material-symbols-outlined engagement-card__icon">stars</span>
                    </div>
                    <h3 class="engagement-card__title">Kemitraan Pasokan Rutin</h3>
                    <p class="engagement-card__desc">Kemitraan jangka panjang untuk ritel dan institusi dengan pasokan berkala serta harga khusus mitra.</p>
                    <ul class="engagement-card__features">
                        <li><span class="material-symbols-outlined">check_circle</span> Harga khusus mitra terdaftar</li>
                        <li><span class="material-symbols-outlined">check_circle</span> Jadwal pengiriman tetap &amp; teratur</li>
                        <li><span class="material-symbols-outlined">check_circle</span> Prioritas ketersediaan stok</li>
                    </ul>
                </div>
                <a class="engagement-card__btn" href="{{ route('contact') }}">Daftar Jadi Mitra</a>
            </div>

            {{-- Model C --}}
            <div class="engagement-card">
                <div>
                    <div class="engagement-card__head">
                        <span class="engagement-card__opt">Opsi C</span>
                        <span class="material-symbols-outlined engagement-card__icon">request_quote</span>
                    </div>
                    <h3 class="engagement-card__title">Pengadaan Massal &amp; Tender</h3>
                    <p class="engagement-card__desc">Untuk kebutuhan pengadaan dalam jumlah besar, proyek, atau institusi melalui proses penawaran yang transparan.</p>
                    <ul class="engagement-card__features">
                        <li><span class="material-symbols-outlined">check</span> Penawaran harga kompetitif</li>
                        <li><span class="material-symbols-outlined">check</span> Kapasitas pasokan besar</li>
                        <li><span class="material-symbols-outlined">check</span> Dukungan administrasi pengadaan</li>
                    </ul>
                </div>
                <a class="engagement-card__btn" href="{{ route('contact') }}">Ajukan Penawaran</a>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="section-page section-page--bg-surface-low">
    <div class="container-page" style="max-width: 56rem;">

        <div class="section-heading">
            <span class="section-tag-pill" style="background:none; padding:0; color:var(--secondary); font-weight:700;">Pertanyaan Sering Diajukan</span>
            <h2 class="section-title-lg section-title-lg--primary">Klarifikasi Seputar Layanan</h2>
        </div>

        @php
            $faqs = [
                ['q' => 'Bagaimana cara memesan barang dari CV Maju Berkah?', 'a' => 'Anda dapat menghubungi tim kami melalui halaman kontak, telepon, atau WhatsApp. Sampaikan jenis barang, jumlah, dan lokasi pengiriman; tim kami akan mengonfirmasi ketersediaan stok, harga, dan estimasi waktu pengiriman.'],
                ['q' => 'Apakah ada minimum pembelian untuk menjadi mitra?', 'a' => 'Untuk pembelian eceran tidak ada minimum besar. Untuk kemitraan pasokan rutin dan pengadaan massal, ketentuan minimum akan disesuaikan dengan jenis barang serta kesepakatan kerja sama.'],
                ['q' => 'Apakah pengiriman menjangkau luar kota?', 'a' => 'Ya. Kami melayani pengiriman ke berbagai kota di Indonesia melalui armada sendiri dan mitra logistik terpercaya. Biaya serta estimasi waktu pengiriman akan diinformasikan sebelum pesanan diproses.'],
                ['q' => 'Bagaimana jika barang yang diterima tidak sesuai?', 'a' => 'Kami menyediakan layanan penanganan keluhan dan retur. Hubungi tim layanan kami dengan menyertakan bukti pesanan dan foto barang, lalu kami akan memproses penukaran atau penyelesaian sesuai ketentuan.'],
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
                    <span class="cta-block__pill">Butuh Pasokan Barang?</span>
                    <h2 class="cta-block__title">Diskusikan Kebutuhan Pengadaan Bersama Tim Kami</h2>
                    <p class="cta-block__desc">
                        Hubungi kami untuk informasi ketersediaan barang, harga terbaru, dan jadwal pengiriman ke lokasi Anda.
                    </p>
                </div>
                <div class="cta-block__actions">
                    <a href="{{ route('contact') }}" class="btn-page btn-page--primary">
                        Hubungi Kami
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <button class="btn-page btn-page--outline-white" onclick="alert('Katalog Layanan CV Maju Berkah (PDF) sedang diunduh.');" type="button">
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
