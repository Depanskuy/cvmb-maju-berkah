@extends('layouts.public')

@section('title', 'Karier — CV Maju Sukses')

@section('content')

{{-- Hero --}}
<section class="career-hero" style="padding: 3rem 0 2.5rem;">
    <div class="career-hero__glow-1"></div>
    <div class="career-hero__glow-2"></div>

    <div class="container-public career-hero__inner">
        <nav class="career-hero__breadcrumb">
            <a href="{{ route('home') }}">
                <span class="material-symbols-outlined">home</span>
                <span>Beranda</span>
            </a>
            <span class="career-hero__breadcrumb-sep">/</span>
            <span class="career-hero__breadcrumb-current">Karir</span>
        </nav>

        <div style="max-width:48rem;">
            <div class="career-hero__badges">
                <span class="career-hero__badge career-hero__badge--neutral">
                    Budaya Kerja Inklusif &amp; Berorientasi Dampak
                </span>
            </div>
            <h1 class="career-hero__title">Karier di CV Maju Sukses</h1>
            <p style="font-size:17px; line-height:1.7; color:rgba(255,255,255,0.85); margin:0;">
                Temukan posisi yang sesuai dengan keahlianmu dan jadilah bagian dari
                transformasi bisnis terdepan di Indonesia.
            </p>
        </div>
    </div>
</section>

{{-- Filter --}}
<section class="container-public" style="margin-top:-2rem; position:relative; z-index:20;">
    <div style="background:var(--surface-container-lowest); border-radius:var(--radius-2xl); padding:1.25rem; box-shadow:0 10px 30px rgba(0,53,95,0.08);">
        <form action="{{ route('careers.index') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:0.75rem;">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari posisi, kata kunci..."
                   style="flex:1; min-width:200px; padding:0.75rem 1rem; background:var(--surface-container-low); border:1px solid transparent; border-radius:var(--radius-md); font-family:inherit; font-size:15px; outline:none;">

            <select name="type"
                    style="padding:0.75rem 1rem; background:var(--surface-container-low); border:1px solid transparent; border-radius:var(--radius-md); font-family:inherit; font-size:15px; outline:none;">
                <option value="">Semua Tipe</option>
                <option value="full_time" {{ request('type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                <option value="part_time" {{ request('type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                <option value="internship" {{ request('type') === 'internship' ? 'selected' : '' }}>Magang</option>
                <option value="contract" {{ request('type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
            </select>

            <button type="submit"
                    style="padding:0.75rem 1.5rem; background:var(--primary); color:#fff; border:none; border-radius:var(--radius-md); font-family:var(--font-display); font-size:15px; font-weight:600; cursor:pointer;">
                Cari
            </button>
        </form>
    </div>
</section>

{{-- List --}}
<section style="padding:3rem 0; background:var(--surface);">
    <div class="container-public">

        <div style="margin-bottom:1.5rem;">
            <h2 class="section-title" style="font-size:24px;">
                {{ $jobs->total() }} Lowongan Aktif
            </h2>
        </div>

        @if ($jobs->isEmpty())
            <div class="empty-state">
                <span class="material-symbols-outlined">work_off</span>
                <p>Belum ada lowongan yang sesuai dengan pencarian Anda.</p>
            </div>
        @else
            <div class="featured-jobs__grid">
                @foreach ($jobs as $job)
                    <a href="{{ route('careers.show', $job) }}" class="job-card">
                        <div class="job-card__badges">
                            <span class="job-card__badge">
                                <span class="job-card__badge-dot"></span>
                                {{ $job->employment_type_label }}
                            </span>
                        </div>

                        <h3 class="job-card__title">{{ $job->title }}</h3>
                        <span class="job-card__category">{{ $job->category }}</span>

                        <div class="job-card__meta">
                            <span class="job-card__meta-item">
                                <span class="material-symbols-outlined">location_on</span>
                                {{ $job->location }}
                            </span>
                            <span class="job-card__meta-item job-card__meta-item--deadline">
                                <span class="material-symbols-outlined">hourglass_top</span>
                                {{ $job->deadline->format('d M Y') }}
                            </span>
                        </div>

                        <p class="job-card__desc">
                            {{ \Illuminate\Support\Str::limit($job->description, 120) }}
                        </p>

                        <div class="job-card__footer">
                            <span class="job-card__action">
                                Lihat Detail
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:center;">
                {{ $jobs->links() }}
            </div>
        @endif

    </div>
</section>

@endsection