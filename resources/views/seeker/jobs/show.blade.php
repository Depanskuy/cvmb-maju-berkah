@extends('layouts.seeker')

@section('title', $jobListing->title . ' — Detail Lowongan')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-5xl mx-auto">

    {{-- Breadcrumb & Back navigation --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('seeker.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('seeker.jobs') }}" class="hover:text-primary transition-colors">Lowongan</a>
                <span>/</span>
                <span class="text-on-surface font-semibold truncate max-w-xs">{{ $jobListing->title }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                {{ $jobListing->title }}
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Divisi {{ $jobListing->category }} &bull; {{ $jobListing->employment_type_label }} &bull; {{ $jobListing->location }}
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('seeker.jobs') }}" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-semibold text-xs flex items-center gap-1.5 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali ke Lowongan</span>
            </a>
            @if ($hasApplied)
                <a href="{{ route('seeker.applications.show', $hasApplied) }}" class="px-4 py-2 rounded-lg bg-emerald-100 text-emerald-800 font-semibold text-xs flex items-center gap-1.5 border border-emerald-300">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    <span>Lihat Status Lamaran Anda</span>
                </a>
            @elseif ($jobListing->canAcceptApplications())
                <a href="{{ route('seeker.jobs.apply', $jobListing) }}" class="px-5 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs flex items-center gap-1.5 hover:bg-primary-container shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[16px]">send</span>
                    <span>Lamar Pekerjaan Ini</span>
                </a>
            @else
                <span class="px-4 py-2 rounded-lg bg-rose-100 text-rose-800 font-semibold text-xs border border-rose-200">
                    Lowongan Ini Telah Ditutup
                </span>
            @endif
        </div>
    </div>

    {{-- 2-Column Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Job Description & Requirements (2 Cols) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Deskripsi Pekerjaan --}}
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-primary text-[20px]">description</span>
                    <h2 class="text-base font-bold font-display text-on-surface">Deskripsi Pekerjaan &amp; Tanggung Jawab</h2>
                </div>
                <div class="text-sm text-on-surface leading-relaxed whitespace-pre-line">
                    {{ $jobListing->description }}
                </div>
            </div>

            {{-- Kualifikasi & Persyaratan --}}
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-primary text-[20px]">checklist</span>
                    <h2 class="text-base font-bold font-display text-on-surface">Kualifikasi &amp; Persyaratan</h2>
                </div>
                <div class="text-sm text-on-surface leading-relaxed whitespace-pre-line">
                    {{ $jobListing->requirements }}
                </div>
            </div>
        </div>

        {{-- Right: Job Specs Sidebar (1 Col) --}}
        <div class="space-y-6">
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    <h2 class="text-base font-bold font-display text-on-surface">Ringkasan Posisi</h2>
                </div>

                <div class="space-y-3.5 text-xs sm:text-sm">
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kategori / Divisi</div>
                        <div class="font-semibold text-on-surface mt-0.5">{{ $jobListing->category }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tipe Pekerjaan</div>
                        <div class="font-semibold text-on-surface mt-0.5">{{ $jobListing->employment_type_label }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Lokasi Kerja</div>
                        <div class="font-semibold text-on-surface mt-0.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">location_on</span>
                            <span>{{ $jobListing->location }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tawaran Gaji</div>
                        <div class="font-bold text-primary mt-0.5">
                            {{ $jobListing->salary_range ?? 'Kompetitif / Sesuai Pengalaman' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Batas Akhir Pendaftaran</div>
                        <div class="font-semibold mt-0.5 flex items-center gap-1 {{ $jobListing->deadline->isPast() ? 'text-rose-600' : 'text-on-surface' }}">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span>{{ $jobListing->deadline->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-outline-variant/20">
                    @if ($hasApplied)
                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                            <strong>Lamaran Terkirim:</strong> Anda telah melamar posisi ini. Silakan pantau tahapan seleksi di menu Lamaran Saya.
                        </div>
                    @elseif ($jobListing->canAcceptApplications())
                        <a href="{{ route('seeker.jobs.apply', $jobListing) }}" class="w-full py-2.5 px-4 rounded-lg bg-primary text-on-primary font-semibold text-xs flex items-center justify-center gap-1.5 hover:bg-primary-container shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[16px]">send</span>
                            <span>Lamar Posisi Ini Sekarang</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
