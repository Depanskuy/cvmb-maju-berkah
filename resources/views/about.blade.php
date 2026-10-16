@extends('layouts.public')

@section('title', 'Tentang Kami — CV Maju Sukses')

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
            <span>Rekam Jejak &amp; Kredibilitas Korporasi</span>
        </div>

        <h1 class="page-hero__title">
            Mendedikasikan Keahlian untuk Pertumbuhan Tangguh Korporasi Indonesia
        </h1>
        <p class="page-hero__subtitle">
            15+ tahun mendampingi 500+ enterprise, BUMN, dan institusi nasional dalam transformasi manajemen, restrukturisasi keuangan, dan tata kelola berstandar dunia.
        </p>

        <div class="page-hero__metrics">
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">15+</div>
                <div class="page-hero__metric-label">Tahun Eksistensi</div>
            </div>
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">500+</div>
                <div class="page-hero__metric-label">Klien Korporasi</div>
            </div>
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">98.4%</div>
                <div class="page-hero__metric-label">Tingkat Retensi</div>
            </div>
            <div class="page-hero__metric">
                <div class="page-hero__metric-value">34 Prov</div>
                <div class="page-hero__metric-label">Jangkauan Nasional</div>
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
                    <span class="material-symbols-outlined">history_edu</span>
                    Perjalanan Kami Sejak 2010
                </div>
                <h2 class="section-title-md">
                    Membangun Standar Baru Konsultasi Manajemen di Era Ketidakpastian
                </h2>
                <div class="about-story__text">
                    <p>Berawal dari kantor kecil di kawasan Jakarta Pusat pada tahun 2010, <strong>CV Maju Sukses</strong> didirikan dengan satu komitmen mendasar: menyediakan layanan advisori strategis berkelas dunia yang membumi dengan dinamika regulasi dan bisnis di Indonesia.</p>
                    <p>Melalui kombinasi ketajaman analisis finansial, integrasi kepatuhan hukum, dan pemahaman operasional yang teruji, kami telah berevolusi dari agensi konsultasi lokal menjadi mitra strategis terpercaya bagi puluhan konglomerasi dan entitas publik terkemuka.</p>
                </div>

                <div class="quote-box">
                    <div class="quote-box__inner">
                        <span class="material-symbols-outlined quote-box__icon">format_quote</span>
                        <div>
                            <blockquote class="quote-box__text">
                                "Kredibilitas bukan dibangun dari janji ekspansi instan, melainkan disiplin tata kelola, transparansi terukur, dan keberanian mengambil keputusan berbasis integritas."
                            </blockquote>
                            <div class="quote-box__author">
                                <div class="quote-box__avatar">BP</div>
                                <div>
                                    <p class="quote-box__name">Bambang Prakoso, S.E., M.B.A.</p>
                                    <p class="quote-box__role">Managing Partner &amp; Co-Founder</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-showcase">
                <div class="about-showcase__image">
                    <img alt="Executive Boardroom Meeting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBjr9R82ZZunNUbDUd7T-LEP_UTZTf0zQtnCK6cYDEHF1cKrPXfUWwYTdbITmGBHlwnIDTmQZiIExD0G4HTstTjyPxCyjtjCX5dTDLy4esKiP0gtlATnW3WbV56b7bSw0815ZQb4KRK_jyYrpLqFjaJRz4cpiD0Jyi9QlLv7Okq0g1N_QUO-CyzwdAPzEy7hbbYJ1R4GPxSEr54jlyQi1nE3TtQLUdNl8ukcLmcJf1FsrCHVSP6Zq_Bw"/>
                </div>

                <div class="about-float-card about-float-card--top">
                    <div class="about-float-card__icon about-float-card__icon--primary">
                        <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <div>
                        <p class="about-float-card__value">15+ Tahun</p>
                        <p class="about-float-card__label">Dedikasi &amp; Pengalaman Nasional</p>
                    </div>
                </div>

                <div class="about-float-card about-float-card--bottom">
                    <div class="about-float-card__icon about-float-card__icon--secondary">
                        <span class="material-symbols-outlined">corporate_fare</span>
                    </div>
                    <div>
                        <p class="about-float-card__value" style="color:var(--on-surface);">500+ Mitra</p>
                        <p class="about-float-card__label">Korporasi &amp; Institusi Publik</p>
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
            <span class="section-tag-pill">Arah &amp; Prinsip Strategis</span>
            <h2 class="section-title-lg">Pondasi Keunggulan Berkelanjutan</h2>
            <p class="section-sub-lg">Mengintegrasikan orientasi visi jangka panjang dengan eksekusi pragmatis berakar pada 4 pilar filosofi kerja.</p>
        </div>

        <div class="visi-misi-grid">
            <div class="visi-misi-card visi-misi-card--primary">
                <div>
                    <div class="visi-misi-card__icon visi-misi-card__icon--primary">
                        <span class="material-symbols-outlined">visibility</span>
                    </div>
                    <h3 class="visi-misi-card__title">Visi Korporat</h3>
                    <p class="visi-misi-card__text">
                        Menjadi akselerator konsultasi manajemen dan tata kelola bisnis paling terpercaya di Asia Tenggara, membentuk institusi bisnis Indonesia yang tangguh, etis, dan berdaya saing global.
                    </p>
                </div>
                <div class="visi-misi-card__footer visi-misi-card__footer--primary">
                    <span class="material-symbols-outlined">check_circle</span>
                    Standar Akuntabilitas Internasional
                </div>
            </div>

            <div class="visi-misi-card visi-misi-card--secondary">
                <div>
                    <div class="visi-misi-card__icon visi-misi-card__icon--secondary">
                        <span class="material-symbols-outlined">track_changes</span>
                    </div>
                    <h3 class="visi-misi-card__title">Misi Strategis</h3>
                    <ul class="visi-misi-card__list">
                        <li>
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Mentransformasi ekosistem korporasi melalui kerangka tata kelola (GCG) yang adaptif dan teruji.
                        </li>
                        <li>
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Mengoptimalkan efisiensi operasional dan kapabilitas finansial klien melalui metodologi data-driven.
                        </li>
                        <li>
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Membangun kapabilitas talenta lokal agar siap bersaing di tingkat enterprise multinasional.
                        </li>
                    </ul>
                </div>
                <div class="visi-misi-card__footer visi-misi-card__footer--secondary">
                    <span class="material-symbols-outlined">stars</span>
                    Fokus Dampak Kuantitatif Nyata
                </div>
            </div>
        </div>

        <div class="pillars-grid">
            @php
                $pillars = [
                    ['icon' => 'policy',      'title' => 'Integritas Tanpa Kompromi',       'desc' => 'Menegakkan standar etika tertinggi dalam setiap audit, rekomendasi kepatuhan, serta pengelolaan data rahasia klien.',  'tag' => 'Pilar 01 • Etika Mutlak'],
                    ['icon' => 'query_stats', 'title' => 'Presisi Analitis & Berbasis Data', 'desc' => 'Seluruh panduan konsultasi berlandaskan validasi fakta terperinci, simulasi risiko kuantitatif, dan riset pasar mendalam.', 'tag' => 'Pilar 02 • Validasi Fakta'],
                    ['icon' => 'lightbulb',   'title' => 'Inovasi Berkelanjutan',           'desc' => 'Merancang solusi yang tidak hanya menyelesaikan krisis hari ini, tetapi juga menciptakan daya adaptasi masa depan yang kokoh.', 'tag' => 'Pilar 03 • Daya Adaptasi'],
                    ['icon' => 'handshake',   'title' => 'Kemitraan Berdampak Nyata',       'desc' => 'Kami bertindak sebagai perpanjangan tangan manajemen eksekutif klien—berkomitmen bersama hingga tujuan tercapai.',          'tag' => 'Pilar 04 • Tanggung Jawab'],
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
            <h2 class="section-title-lg">Dewan Pakar &amp; Pimpinan Eksekutif</h2>
            <p class="section-sub-lg" style="max-width: 42rem;">Dipimpin oleh para profesional berpengalaman lintas industri yang memiliki rekam jejak terverifikasi dalam restrukturisasi korporat dan transformasi nasional.</p>
        </div>

        <div class="leaders-grid">
            @php
                $leaders = [
                    ['name' => 'Bambang Prakoso, S.E., M.B.A.',  'role' => 'Managing Partner / Direktur Utama', 'spec' => 'Spesialisasi: Perencanaan Strategis Korporasi, Merger & Akuisisi, serta Tata Kelola Enterprise (GCG).',                 'tag' => '20+ Thn Pengalaman'],
                    ['name' => 'Ratna Dewi, Ak., M.Ak., CA',      'role' => 'Partner Konsultasi Keuangan',       'spec' => 'Spesialisasi: Restrukturisasi Utang Korporasi, Valuasi Aset Strategis, dan Audit Finansial Komprehensif.',            'tag' => 'Mantan Direktur Big 4'],
                    ['name' => 'Hendra Kurniawan, S.T., M.Sc.',   'role' => 'Partner Transformasi Digital',       'spec' => 'Spesialisasi: Otomasi Proses Operasional, Integrasi Cloud ERP, dan Optimalisasi Efisiensi Supply Chain.',             'tag' => 'Ahli Lean Six Sigma'],
                    ['name' => 'Siti Aminah, S.H., LL.M.',        'role' => 'Legal & Governance Advisor',         'spec' => 'Spesialisasi: Regulasi Hukum Bisnis, Mitigasi Risiko Kepatuhan BUMN & Swasta, serta Audit Kontrak Komersial.',        'tag' => 'Konsultan Hukum Resmi'],
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
                    Kepatuhan &amp; Akreditasi Resmi
                </div>
                <h3 class="certifications-card__title">Standar Mutu Mutlak Tanpa Toleransi</h3>
                <p class="certifications-card__desc">
                    Seluruh metodologi konsultasi, tata kelola data informasi klien, serta kerangka kerja operasional kami diaudit secara berkala untuk memenuhi standar keandalan global.
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
                        <span class="material-symbols-outlined">lock</span>
                    </div>
                    <div>
                        <p class="cert-badge__name">ISO 27001:2022</p>
                        <p class="cert-badge__sub">Keamanan Data Klien</p>
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
                        Sesi Diskusi Eksklusif
                    </span>
                    <h2 class="cta-block__title">Siap Mengakselerasi Potensi Bisnis Anda?</h2>
                    <p class="cta-block__desc">
                        Diskusikan tantangan tata kelola, ekspansi pasar, atau efisiensi finansial korporasi Anda bersama Managing Partner CV Maju Sukses.
                    </p>
                </div>

                <div class="cta-block__actions">
                    <a href="{{ route('contact') }}" class="btn-page btn-page--primary">
                        <span class="material-symbols-outlined">chat</span>
                        Jadwalkan Konsultasi Gratis
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