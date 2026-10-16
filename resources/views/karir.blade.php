{{-- resources/views/karir.blade.php --}}
@extends('layouts.public')

@section('title', 'Karier — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full">

    {{-- 1. PAGE HEADER --}}
    <section class="relative w-full bg-gradient-to-br from-primary-container via-primary to-tertiary overflow-hidden text-surface-container-lowest py-space-xl md:py-24">
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(circle at 20% 30%, #ffffff 1px, transparent 1px), radial-gradient(circle at 80% 70%, #feae2c 1px, transparent 1px); background-size: 32px 32px;"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-secondary-container/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-20 w-80 h-80 rounded-full bg-on-primary-container/20 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-[1280px] mx-auto px-margin-mobile md:px-margin-tablet lg:px-margin flex flex-col items-start gap-space-md">
            <nav aria-label="Breadcrumb" class="flex items-center gap-space-xs text-tertiary-fixed-dim font-label-md text-label-md">
                <a href="{{ route('home') }}" class="hover:text-surface-container-lowest transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span> Beranda
                </a>
                <span class="text-tertiary-fixed-dim/60">/</span>
                <span class="text-surface-container-lowest font-semibold">Karir</span>
            </nav>

            <div class="inline-flex items-center gap-space-xs px-3 py-1.5 rounded-full bg-surface-container-lowest/10 backdrop-blur-md text-surface-container-lowest shadow-sm">
                <span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse"></span>
                <span class="font-label-sm text-label-sm tracking-wide">Budaya Kerja Inklusif & Berorientasi Dampak</span>
            </div>

            <div class="max-w-3xl flex flex-col gap-space-sm">
                <h1 class="font-headline-lg text-headline-lg text-surface-container-lowest tracking-tight">Karier di CV Maju Sukses</h1>
                <p class="font-body-lg text-body-lg text-surface-container-lowest/90 leading-relaxed">
                    Temukan posisi yang sesuai dengan keahlianmu dan jadilah bagian dari transformasi bisnis terdepan di Indonesia.
                </p>
            </div>
        </div>
    </section>

    {{-- 2. STICKY FILTER --}}
    <section class="relative max-w-[1280px] w-full mx-auto px-margin-mobile md:px-margin-tablet lg:px-margin -mt-8 md:-mt-12 z-20">
        <div class="bg-surface-container-lowest rounded-xl p-space-md md:p-space-lg shadow-xl shadow-tertiary/5">
            <form class="flex flex-col lg:flex-row gap-space-md items-stretch lg:items-center" id="job-filter-form">
                <div class="relative flex-grow flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">search</span>
                    <input type="text" placeholder="Cari posisi, kata kunci, atau keahlian..."
                           class="w-full pl-11 pr-4 py-3 bg-surface-container-low text-on-surface rounded-lg font-body-md text-body-md focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary-container transition-all">
                </div>

                @foreach ([
                    ['schedule',   'Semua Tipe Kerja', ['Full-time', 'Kontrak', 'Internship', 'Remote']],
                    ['location_on','Semua Lokasi',     ['SCBD, Jakarta', 'Jakarta Pusat', 'Surabaya', 'Fleksibel Hybrid']],
                    ['domain',     'Semua Departemen', ['Financial Consulting', 'Digital Transformation', 'Business Strategy', 'Risk & Governance']],
                ] as [$icon, $placeholder, $options])
                    <div class="relative min-w-[170px]">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">{{ $icon }}</span>
                        <select class="w-full appearance-none pl-10 pr-9 py-3 bg-surface-container-low text-on-surface rounded-lg font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary-container cursor-pointer">
                            <option value="">{{ $placeholder }}</option>
                            @foreach ($options as $opt)
                                <option value="{{ Str::slug($opt) }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                    </div>
                @endforeach

                <button type="button" class="flex items-center justify-center gap-2 bg-primary-container hover:bg-primary text-on-primary font-label-md text-label-md px-6 py-3.5 rounded-lg shadow-sm transition-all active:scale-95 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">tune</span> Cari Lowongan
                </button>
            </form>
        </div>
    </section>

    {{-- 3. JOB LIST --}}
    <main class="w-full max-w-[1280px] mx-auto px-margin-mobile md:px-margin-tablet lg:px-margin py-space-xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
            <section class="lg:col-span-8 flex flex-col gap-space-md">
                <div class="flex items-center justify-between pb-space-sm">
                    <span class="font-title-lg text-title-lg text-on-surface">Menampilkan {{ count($jobs ?? []) }} lowongan aktif</span>
                </div>

                <div class="flex flex-col gap-space-md">
                    @foreach ($jobs ?? [] as $job)
                        <x-job-card :job="$job" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                {{ $jobs->links() ?? '' }}
            </section>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 flex flex-col gap-space-md lg:sticky lg:top-24">
                @include('partials.karir.tips-melamar')
                @include('partials.karir.kultur-benefit')
                @include('partials.karir.newsletter')
                @include('partials.karir.quick-help')
            </aside>
        </div>
    </main>

</div>
@endsection