@extends('layouts.public')

@section('title', 'Tentang Kami — CV Maju Berkah')

@section('content')

{{-- HERO --}}
<section class="page-hero">
    <div class="page-hero__glow-1"></div>
    <div class="page-hero__glow-2"></div>

    <div class="container-page page-hero__inner">
        <nav class="page-hero__breadcrumb">
            <a href="{{ route('home') }}">
                <span class="material-symbols-outlined">home</span>
                Beranda
            </a>
            <span class="page-hero__breadcrumb-sep">/</span>
            <span class="page-hero__breadcrumb-current">Tentang Kami</span>
        </nav>

        <div class="page-hero__pill">
            <span class="page-hero__pill-dot"></span>
            <span>Distribusi Barang &amp; Kebutuhan Usaha</span>
        </div>

        <h1 class="page-hero__title">
            Menyalurkan Barang Berkualitas untuk Pertumbuhan Usaha Indonesia
        </h1>
        <p class="page-hero__subtitle">
            15+ tahun menghubungkan produsen terpercaya dengan ribuan mitra usaha, ritel, dan institusi melalui jaringan distribusi yang andal di seluruh Indonesia.
        </p>

        <div class="page-hero__metrics">
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">15+</div>
                <div class="page-hero__metric-label">Tahun Pengalaman</div>
            </div>
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">2.000+</div>
                <div class="page-hero__metric-label">Mitra Usaha</div>
            </div>
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">200+</div>
                <div class="page-hero__metric-label">Pemasok Terpercaya</div>
            </div>
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">34 Prov</div>
                <div class="page-hero__metric-label">Jangkauan Distribusi</div>
            </div>
        </div>
    </div>
</section>

{{-- SEJARAH --}}
<section class="section-page section-page--bg-surface">
    <div class="container-page">
        <div class="about-story__grid">

            <div class="about-story__content">
                <div class="about-story__eyebrow">
                    <span class="material-symbols-outlined">local_shipping</span>
                    Perjalanan Kami Sejak 2010
                </div>
                <h2 class="section-title-md">
                    Membangun Jaringan Distribusi yang Andal dan Terpercaya
                </h2>
                <div class="about-story__text">
                    <p>Berawal dari sebuah gudang kecil di Jakarta pada tahun 2010, <strong>CV Maju Berkah</strong> didirikan dengan satu komitmen mendasar: memastikan barang dan kebutuhan usaha sampai ke tangan mitra secara tepat waktu, tepat jumlah, dan dengan harga bersaing.</p>
                    <p>Dengan dukungan jaringan gudang, armada logistik, dan kemitraan bersama ratusan pemasok terpercaya, kami tumbuh menjadi mitra distribusi andal bagi ritel, UMKM, perkantoran, hingga institusi di seluruh Indonesia.</p>
                </div>

                <div class="quote-box">
                    <div class="quote-box__inner">
                        <span class="material-symbols-outlined quote-box__icon">format_quote</span>
                        <div>
                            <blockquote class="quote-box__text">
                                "Kepercayaan mitra dibangun dari ketepatan pengiriman, konsistensi kualitas barang, dan pelayanan yang andal setiap hari."
                            </blockquote>
                            <div class="quote-box__author">
                                <div class="quote-box__avatar">HW</div>
                                <div>
                                    <p class="quote-box__name">Hendra Wijaya, S.E.</p>
                                    <p class="quote-box__role">Direktur Utama &amp; Pendiri</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-showcase">
                <div class="about-showcase__image">
                    <img alt="Tim CV Maju Berkah berdiskusi di kantor" src="{{ asset('images/team-meeting.jpg') }}"/>
                </div>

                <div class="about-float-card about-float-card--top">
                    <div class="about-float-card__icon about-float-card__icon--primary">
                        <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <div>
                        <p class="about-float-card__value">15+ Tahun</p>
                        <p class="about-float-card__label">Pengalaman Distribusi Nasional</p>
                    </div>
                </div>

                <div class="about-float-card about-float-card--bottom">
                    <div class="about-float-card__icon about-float-card__icon--secondary">
                        <span class="material-symbols-outlined">warehouse</span>
                    </div>
                    <div>
                        <p class="about-float-card__value" style="color:var(--on-surface);">2.000+ Mitra</p>
                        <p class="about-float-card__label">Ritel, UMKM &amp; Institusi</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- VISI MISI + PILAR --}}
<section class="section-page section-page--bg-surface-low">
    <div class="container-page">

        <div class="section-heading">
            <span class="section-tag-pill">Arah &amp; Prinsip Kerja</span>
            <h2 class="section-title-lg">Pondasi Layanan Distribusi Kami</h2>
            <p class="section-sub-lg">Memadukan orientasi pelayanan jangka panjang dengan eksekusi operasional yang berakar pada 4 pilar kerja.</p>
        </div>

        <div class="visi-misi-grid">
            <div class="visi-misi-card visi-misi-card--primary">
                <div>
                    <div class="visi-misi-card__icon visi-misi-card__icon--primary">
                        <span class="material-symbols-outlined">visibility</span>
                    </div>
                    <h3 class="visi-misi-card__title">Visi Perusahaan</h3>
                    <p class="visi-misi-card__text">
                        Menjadi mitra distribusi barang dan kebutuhan usaha paling andal di Indonesia, yang menghubungkan produsen berkualitas dengan pelaku usaha di seluruh negeri.
                    </p>
                </div>
                <div class="visi-misi-card__footer visi-misi-card__footer--primary">
                    <span class="material-symbols-outlined">check_circle</span>
                    Standar Layanan Distribusi Nasional
                </div>
            </div>

            <div class="visi-misi-card visi-misi-card--secondary">
                <div>
                    <div class="visi-misi-card__icon visi-misi-card__icon--secondary">
                        <span class="material-symbols-outlined">track_changes</span>
                    </div>
                    <h3 class="visi-misi-card__title">Misi Kami</h3>
                    <ul class="visi-misi-card__list">
                        <li>
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Menyediakan pasokan barang dan kebutuhan usaha yang berkualitas dengan harga bersaing.
                        </li>
                        <li>
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Menjaga ketepatan waktu, jumlah, dan kondisi barang dalam setiap pengiriman.
                        </li>
                        <li>
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Membangun jaringan pemasok dan mitra usaha yang luas serta saling menguntungkan.
                        </li>
                    </ul>
                </div>
                <div class="visi-misi-card__footer visi-misi-card__footer--secondary">
                    <span class="material-symbols-outlined">stars</span>
                    Fokus pada Ketepatan &amp; Kepuasan Mitra
                </div>
            </div>
        </div>

        <div class="pillars-grid">
            @php
                $pillars = [
                    ['icon' => 'handshake',    'title' => 'Integritas & Kepercayaan', 'desc' => 'Menjaga amanah mitra melalui transparansi harga, kualitas barang, dan komunikasi yang jujur.', 'tag' => 'Pilar 01 • Amanah'],
                    ['icon' => 'local_shipping','title' => 'Ketepatan & Kecepatan',  'desc' => 'Memastikan setiap pesanan diproses dan dikirim sesuai jadwal dengan gudang serta armada yang andal.', 'tag' => 'Pilar 02 • Tepat Waktu'],
                    ['icon' => 'verified',     'title' => 'Kualitas Terjamin',        'desc' => 'Setiap produk melewati pemeriksaan mutu sebelum sampai ke tangan mitra usaha.', 'tag' => 'Pilar 03 • Kendali Mutu'],
                    ['icon' => 'diversity_3',  'title' => 'Kemitraan Jangka Panjang', 'desc' => 'Tumbuh bersama mitra melalui pelayanan yang responsif dan dukungan yang berkelanjutan.', 'tag' => 'Pilar 04 • Tumbuh Bersama'],
                ];
            @endphp

            @foreach ($pillars as $p)
                <div class="pillar-card">
                    <div class="pillar-card__icon">
                        <span class="material-symbols-outlined">{{ $p['icon'] }}</span>
                    </div>
                    <h4 class="pillar-card__title">{{ $p['title'] }}</h4>
                    <p class="pillar-card__desc">{{ $p['desc'] }}</p>
                    <div class="pillar-card__tag">{{ $p['tag'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- LEADERSHIP --}}
<section class="section-page section-page--bg-surface">
    <div class="container-page">

        <div class="section-heading" style="align-items: flex-start; text-align: left; margin-left: 0; margin-right: 0; max-width: none;">
            <span class="section-tag-pill">Kepemimpinan</span>
            <h2 class="section-title-lg">Tim Manajemen &amp; Operasional</h2>
            <p class="section-sub-lg" style="max-width: 42rem;">Dipimpin oleh tim berpengalaman di bidang pengadaan, pergudangan, dan distribusi yang berkomitmen memastikan kepuasan mitra usaha.</p>
        </div>

        <div class="leaders-grid">
            @php
                $leaders = [
                    ['name' => 'Hendra Wijaya, S.E.',      'role' => 'Direktur Utama & Pendiri', 'spec' => 'Fokus: Strategi Distribusi, Pengembangan Jaringan Mitra, dan Manajemen Rantai Pasok.',      'tag' => '15+ Thn Pengalaman'],
                    ['name' => 'Ratna Dewi, S.E., M.M.',   'role' => 'Direktur Operasional',      'spec' => 'Fokus: Manajemen Pergudangan, Logistik, dan Optimalisasi Rute Pengiriman.',                  'tag' => 'Ahli Supply Chain'],
                    ['name' => 'Budi Santoso, S.T.',       'role' => 'Manajer Pengadaan',         'spec' => 'Fokus: Pengadaan Barang, Negosiasi dengan Pemasok, dan Kendali Kualitas Produk.',            'tag' => 'Jaringan 200+ Pemasok'],
                    ['name' => 'Siti Aminah, S.E.',        'role' => 'Manajer Pelayanan Mitra',   'spec' => 'Fokus: Layanan Pelanggan, Penanganan Pesanan, dan Retensi Mitra Usaha.',                     'tag' => 'Layanan Responsif'],
                ];
            @endphp

            @foreach ($leaders as $l)
                <div class="leader-card">
                    <div class="leader-card__photo">
                        <span class="leader-card__initials">{{ strtoupper(substr($l['name'], 0, 2)) }}</span>
                    </div>
                    <div class="leader-card__body">
                        <h3 class="leader-card__name">{{ $l['name'] }}</h3>
                        <p class="leader-card__role">{{ $l['role'] }}</p>
                        <div class="leader-card__divider"></div>
                        <p class="leader-card__spec">{{ $l['spec'] }}</p>
                        <span class="leader-card__tag">{{ $l['tag'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SERTIFIKASI --}}
<section class="section-page section-page--bg-surface-low" style="padding: 2.5rem 0;">
    <div class="container-page">
        <div class="certifications-card">
            <div class="certifications-card__content">
                <div class="certifications-card__eyebrow">
                    <span class="material-symbols-outlined">verified</span>
                    Kepatuhan &amp; Sertifikasi
                </div>
                <h3 class="certifications-card__title">Standar Mutu dalam Setiap Proses</h3>
                <p class="certifications-card__desc">
                    Seluruh proses pengadaan, penyimpanan, dan pengiriman kami mengikuti prosedur mutu untuk menjamin barang sampai dengan aman, lengkap, dan tepat waktu.
                </p>
            </div>

            <div class="certifications-card__grid">
                <div class="cert-badge">
                    <div class="cert-badge__icon cert-badge__icon--primary">
                        <span class="material-symbols-outlined">workspace_premium</span>
                    </div>
                    <div>
                        <p class="cert-badge__name">ISO 9001:2015</p>
                        <p class="cert-badge__sub">Sistem Manajemen Mutu</p>
                    </div>
                </div>
                <div class="cert-badge">
                    <div class="cert-badge__icon cert-badge__icon--primary">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                    <div>
                        <p class="cert-badge__name">Standar Pergudangan</p>
                        <p class="cert-badge__sub">Penyimpanan &amp; Kendali Stok</p>
                    </div>
                </div>
                <div class="cert-badge">
                    <div class="cert-badge__icon cert-badge__icon--secondary">
                        <span class="material-symbols-outlined">badge</span>
                    </div>
                    <div>
                        <p class="cert-badge__name">KADIN &amp; AKMI</p>
                        <p class="cert-badge__sub">Rekanan Terakreditasi</p>
                    </div>
                </div>
            </div>
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
                    <span class="cta-block__pill">
                        <span class="material-symbols-outlined" style="font-size:16px;">calendar_month</span>
                        Mulai Bermitra
                    </span>
                    <h2 class="cta-block__title">Butuh Pasokan Barang untuk Usaha Anda?</h2>
                    <p class="cta-block__desc">
                        Diskusikan kebutuhan pengadaan dan distribusi barang bersama tim CV Maju Berkah. Kami siap membantu pasokan usaha Anda.
                    </p>
                </div>

                <div class="cta-block__actions">
                    <a href="{{ route('contact') }}" class="btn-page btn-page--primary">
                        <span class="material-symbols-outlined">chat</span>
                        Hubungi Kami
                    </a>
                    <a href="{{ route('services') }}" class="btn-page btn-page--outline-white">
                        Lihat Layanan Kami
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
