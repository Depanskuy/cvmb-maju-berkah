@extends('layouts.public')

@section('title', $jobListing->title . ' — CV Maju Sukses')

@section('content')

{{-- ============ HERO ============ --}}
<section class="career-hero">
    <div class="career-hero__glow-1"></div>
    <div class="career-hero__glow-2"></div>

    <div class="container-public career-hero__inner">

        {{-- Breadcrumb --}}
        <nav class="career-hero__breadcrumb">
            <a href="{{ route('careers.index') }}">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Kembali ke Lowongan</span>
            </a>
            <span class="career-hero__breadcrumb-sep">•</span>
            <span class="career-hero__breadcrumb-current">Divisi {{ $jobListing->category }}</span>
        </nav>

        <div class="career-hero__flex">

            {{-- Left --}}
            <div>
                <div class="career-hero__badges">

                    @if ($jobListing->isOpenForApplication())
                        <span class="career-hero__badge career-hero__badge--green">
                            <span class="career-hero__badge-dot"></span>
                            {{ $jobListing->employment_type_label }}
                        </span>
                    @else
                        <span class="career-hero__badge career-hero__badge--neutral">
                            {{ $jobListing->status_label }}
                        </span>
                    @endif

                    <span class="career-hero__badge career-hero__badge--neutral">
                        {{ $jobListing->category }}
                    </span>

                    <span class="career-hero__badge career-hero__badge--gold">
                        Prioritas Perekrutan
                    </span>
                </div>

                <h1 class="career-hero__title">{{ $jobListing->title }}</h1>

                <div class="career-hero__meta">
                    <div class="career-hero__meta-item">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>{{ $jobListing->location }}</span>
                    </div>
                    <div class="career-hero__meta-item">
                        <span class="material-symbols-outlined">event</span>
                        <span>Batas Lamaran: {{ $jobListing->deadline->format('d M Y') }}</span>
                    </div>
                    @if ($jobListing->salary_min && $jobListing->salary_max)
                        <div class="career-hero__meta-item">
                            <span class="material-symbols-outlined">payments</span>
                            <span>Rp {{ number_format($jobListing->salary_min, 0, ',', '.') }} - {{ number_format($jobListing->salary_max, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: CTA --}}
            <div class="career-hero__cta">
                @if ($jobListing->isOpenForApplication())
                    @auth
                        @if (auth()->user()->isAdmin())
                            <span class="career-hero__btn-primary" style="opacity:0.75; cursor:not-allowed; background:#47607a;" title="Admin perusahaan tidak dapat melamar pekerjaan">
                                <span class="material-symbols-outlined" style="font-size:18px;">admin_panel_settings</span>
                                <span>Akun Admin (Tidak Dapat Melamar)</span>
                            </span>
                        @else
                            <a href="{{ route('careers.apply.create', $jobListing) }}" class="career-hero__btn-primary">
                                <span>Lamar Sekarang</span>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('careers.apply.create', $jobListing) }}" class="career-hero__btn-primary">
                            <span>Lamar Sekarang</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    @endauth
                @else
                    <span class="career-hero__btn-primary" style="opacity:0.5; cursor:not-allowed;">
                        <span>Lowongan Ditutup</span>
                    </span>
                @endif

                <a href="{{ route('careers.index') }}" class="career-hero__btn-ghost">
                    <span class="material-symbols-outlined">list</span>
                    <span>Semua Lowongan</span>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ============ QUICK STRIP ============ --}}
<section class="career-strip">
    <div class="container-public">
        <div class="career-strip__inner">

            <div class="career-strip__left">
                <div class="career-strip__item">
                    <span class="material-symbols-outlined">badge</span>
                    <span>{{ $jobListing->employment_type_label }}</span>
                </div>
                <div class="career-strip__item">
                    <span class="material-symbols-outlined">schedule</span>
                    <span>Diposting {{ $jobListing->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="career-strip__right">
                <span class="career-strip__dot"></span>
                <span>
                    @if ($jobListing->isOpenForApplication())
                        Posisi aktif • Terbuka untuk dilamar
                    @else
                        Lowongan sudah ditutup
                    @endif
                </span>
            </div>

        </div>
    </div>
</section>

{{-- ============ MAIN ============ --}}
<section class="career-main">
    <div class="container-public">
        <div class="career-main__grid">

            {{-- ================= LEFT COLUMN ================= --}}
            <div class="career-content">

                {{-- Success alert --}}
                @if (session('apply_success'))
                    <div class="apply-alert apply-alert--success" style="margin-bottom: 1.5rem;">
                        <span class="material-symbols-outlined">check_circle</span>
                        <div>
                            <p class="apply-alert__title">Lamaran Berhasil Terkirim!</p>
                            <p class="apply-alert__msg">{{ session('apply_success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Deskripsi --}}
                <article class="content-card">
                    <div class="content-card__accent"></div>
                    <h2 class="content-card__title">
                        <span class="material-symbols-outlined">description</span>
                        Deskripsi Pekerjaan
                    </h2>
                    <div class="content-card__body">
                        {!! nl2br(e($jobListing->description)) !!}
                    </div>
                </article>

                {{-- Kualifikasi --}}
                <article class="content-card">
                    <div class="content-card__accent content-card__accent--gold"></div>
                    <h2 class="content-card__title">
                        <span class="material-symbols-outlined">verified_user</span>
                        Kualifikasi &amp; Persyaratan
                    </h2>
                    <div class="content-card__body">
                        <p class="requirements-list">{{ $jobListing->requirements }}</p>
                    </div>
                </article>

                {{-- Info Tambahan --}}
                <article class="content-card">
                    <div class="content-card__accent"></div>
                    <h2 class="content-card__title">
                        <span class="material-symbols-outlined">info</span>
                        Informasi Tambahan
                    </h2>
                    <div class="content-card__body">
                        <div class="content-callout">
                            <span class="material-symbols-outlined">lightbulb</span>
                            <p>
                                <strong>Proses seleksi:</strong> Tim rekrutmen akan meninjau berkas Anda
                                dalam 3–5 hari kerja. Hanya kandidat yang lolos tahap awal yang akan dihubungi
                                untuk tahap selanjutnya.
                            </p>
                        </div>
                    </div>
                </article>



            </div>

            {{-- ================= RIGHT SIDEBAR ================= --}}
            <aside class="career-sidebar">

                {{-- Ringkasan --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card__title">
                        <span class="material-symbols-outlined">info</span>
                        Ringkasan Lowongan
                    </h3>

                    <div class="summary-list">

                        <div class="summary-row">
                            <span class="material-symbols-outlined">badge</span>
                            <div>
                                <div class="summary-row__label">Tipe Pekerjaan</div>
                                <div class="summary-row__value">{{ $jobListing->employment_type_label }}</div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span class="material-symbols-outlined">location_on</span>
                            <div>
                                <div class="summary-row__label">Lokasi</div>
                                <div class="summary-row__value">{{ $jobListing->location }}</div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span class="material-symbols-outlined">domain</span>
                            <div>
                                <div class="summary-row__label">Kategori</div>
                                <div class="summary-row__value">{{ $jobListing->category }}</div>
                            </div>
                        </div>

                        @if ($jobListing->salary_min && $jobListing->salary_max)
                            <div class="summary-row">
                                <span class="material-symbols-outlined">payments</span>
                                <div>
                                    <div class="summary-row__label">Rentang Gaji</div>
                                    <div class="summary-row__value summary-row__value--primary">
                                        Rp {{ number_format($jobListing->salary_min, 0, ',', '.') }} - {{ number_format($jobListing->salary_max, 0, ',', '.') }}
                                    </div>
                                    <div class="summary-row__sub">Per bulan</div>
                                </div>
                            </div>
                        @endif

                        <div class="summary-row">
                            <span class="material-symbols-outlined">event_busy</span>
                            <div>
                                <div class="summary-row__label">Batas Lamaran</div>
                                <div class="summary-row__value {{ $jobListing->isOpenForApplication() ? '' : 'summary-row__value--danger' }}">
                                    {{ $jobListing->deadline->format('d F Y') }}
                                </div>
                            </div>
                        </div>

                    </div>

                    @if ($jobListing->isOpenForApplication())
                        @auth
                            @if (auth()->user()->isAdmin())
                                <div style="margin-top:1rem; padding:0.75rem 1rem; border-radius:8px; background:rgba(0,39,78,0.06); font-size:13px; color:var(--primary); text-align:center; display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                                    <span class="material-symbols-outlined" style="font-size:18px;">admin_panel_settings</span>
                                    <span>Akun Admin Perusahaan tidak dapat melamar lowongan.</span>
                                </div>
                            @else
                                <a href="{{ route('careers.apply.create', $jobListing) }}" class="sidebar-cta">
                                    <span>Lamar Sekarang</span>
                                    <span class="material-symbols-outlined">north_east</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('careers.apply.create', $jobListing) }}" class="sidebar-cta">
                                <span>Lamar Sekarang</span>
                                <span class="material-symbols-outlined">north_east</span>
                            </a>
                        @endauth
                    @endif
                </div>

                {{-- Profil Perusahaan --}}
                <div class="sidebar-card">
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                        <div style="width:3rem; height:3rem; border-radius:0.75rem; background:var(--primary); color:#fff; font-family:var(--font-display); font-weight:700; display:flex; align-items:center; justify-content:center; font-size:1.125rem;">
                            MS
                        </div>
                        <div>
                            <div style="font-family:var(--font-display); font-size:18px; font-weight:700; color:var(--primary);">
                                {{ $company->name }}
                            </div>
                            <div style="display:flex; align-items:center; gap:0.25rem; font-size:12px; color:var(--on-surface-variant);">
                                <span class="material-symbols-outlined" style="font-size:16px; color:var(--primary);">verified</span>
                                <span>Perusahaan Resmi Terdaftar</span>
                            </div>
                        </div>
                    </div>

                    <p style="font-size:13px; line-height:1.6; color:var(--on-surface-variant); margin:0 0 1rem;">
                        {{ \Illuminate\Support\Str::limit($company->description, 180) }}
                    </p>

                    <a href="{{ route('about') }}" class="section-link">
                        <span>Pelajari Profil Perusahaan</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>

                {{-- Hubungi --}}
                <div class="sidebar-card">
                    <h4 class="sidebar-card__title" style="font-size:16px;">
                        <span class="material-symbols-outlined">contact_support</span>
                        Butuh Bantuan?
                    </h4>
                    <p style="font-size:13px; line-height:1.6; color:var(--on-surface-variant); margin:0 0 0.75rem;">
                        Ada pertanyaan seputar proses rekrutmen? Hubungi tim kami:
                    </p>
                    <div class="summary-row" style="border:none; padding:0;">
                        <span class="material-symbols-outlined">mail</span>
                        <div>
                            <div class="summary-row__value" style="font-size:14px; word-break:break-all;">
                                {{ $company->contact_email }}
                            </div>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</section>

@endsection
