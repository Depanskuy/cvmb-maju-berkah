@extends('layouts.seeker')

@section('title', 'Dashboard Pelamar — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success') || session('success_feedback'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') ?? session('success_feedback') }}</span>
            </div>
        </div>
    @endif

    {{-- Banner Sambutan Biru (Identik dengan Admin) --}}
    <div class="p-6 rounded-2xl bg-gradient-to-r from-primary to-primary-container text-on-primary shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 text-white text-xs font-semibold mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Akun Pencari Kerja Terverifikasi</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-white">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-white/80 text-sm mt-1 max-w-xl">
                Pantau proses seleksi berkas, jadwal wawancara, dan temukan peluang karir terbaik di CV Maju Sukses.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <a href="{{ route('seeker.jobs') }}" class="px-4 py-2.5 rounded-lg bg-secondary-container text-on-primary-fixed font-semibold text-sm flex items-center gap-2 hover:brightness-110 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">travel_explore</span>
                <span>Jelajahi Lowongan</span>
            </a>
            <a href="{{ route('seeker.profile.show') }}" class="px-4 py-2.5 rounded-lg bg-white/10 text-white font-semibold text-sm flex items-center gap-2 hover:bg-white/20 transition-all">
                <span class="material-symbols-outlined text-[18px]">account_circle</span>
                <span>Profil &amp; CV Saya</span>
            </a>
        </div>
    </div>

    {{-- Kartu Statistik Lamaran (Konsisten dengan Admin) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Lamaran --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Lamaran</span>
                <span class="material-symbols-outlined text-primary text-[22px]">assignment</span>
            </div>
            <div class="text-3xl font-bold font-display text-on-surface mt-2">
                {{ $stats['total'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Seluruh posisi yang telah dilamar
            </div>
        </div>

        {{-- Menunggu Review --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Menunggu Review</span>
                <span class="material-symbols-outlined text-amber-600 text-[22px]">hourglass_top</span>
            </div>
            <div class="text-3xl font-bold font-display text-amber-600 mt-2">
                {{ $stats['pending'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Sedang dalam antrean screening HR
            </div>
        </div>

        {{-- Proses Seleksi / Interview --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Proses Seleksi</span>
                <span class="material-symbols-outlined text-sky-600 text-[22px]">sync</span>
            </div>
            <div class="text-3xl font-bold font-display text-sky-600 mt-2">
                {{ $stats['processing'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Tahap evaluasi berkas &amp; interview
            </div>
        </div>

        {{-- Diterima --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Diterima</span>
                <span class="material-symbols-outlined text-emerald-600 text-[22px]">check_circle</span>
            </div>
            <div class="text-3xl font-bold font-display text-emerald-600 mt-2">
                {{ $stats['accepted'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Lolos seleksi penerimaan kerja
            </div>
        </div>
    </div>

    {{-- Main Grid: Lamaran Terbaru & Profil Ringkas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Daftar Lamaran Terbaru (2 cols) --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-outline-variant/20 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-base text-on-surface">Lamaran Pekerjaan Terbaru</h2>
                    <p class="text-xs text-on-surface-variant">Status terkini berkas lamaran yang Anda kirimkan</p>
                </div>
                <a href="{{ route('seeker.applications') }}" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                    <span>Lihat Semua ({{ $stats['total'] }})</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Posisi Lowongan</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                        @forelse ($recentApplications as $app)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-on-surface">
                                        {{ $app->jobListing->title ?? 'Posisi Tidak Tersedia' }}
                                    </div>
                                    <div class="text-xs text-on-surface-variant">
                                        Divisi {{ $app->jobListing->category ?? '-' }} • {{ $app->jobListing->location ?? '-' }}
                                    </div>
                                    @if ($app->review_note)
                                        <div class="mt-1 text-[11px] text-primary bg-primary/5 px-2 py-1 rounded border border-primary/15 max-w-sm">
                                            <strong>Catatan HR:</strong> {{ Str::limit($app->review_note, 60) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-on-surface-variant whitespace-nowrap">
                                    <div>{{ $app->created_at->format('d M Y') }}</div>
                                    <div class="text-[11px]">{{ $app->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $badgeClass = match($app->status) {
                                            'pending'    => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'processing' => 'bg-sky-100 text-sky-800 border-sky-200',
                                            'interview'  => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'accepted'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'rejected'   => 'bg-rose-100 text-rose-800 border-rose-200',
                                            default      => 'bg-slate-100 text-slate-800 border-slate-200',
                                        };
                                        if ($app->isWithdrawn()) {
                                            $badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                                        {{ $app->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('seeker.applications.show', $app) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-semibold hover:bg-primary hover:text-white transition-colors">
                                        <span>Detail</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-on-surface-variant">
                                    <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-2 text-outline">
                                        <span class="material-symbols-outlined text-[24px]">inbox</span>
                                    </div>
                                    <div class="font-semibold text-sm text-on-surface">Belum ada lamaran diajukan</div>
                                    <p class="text-xs text-on-surface-variant mt-0.5">Pilih lowongan kerja yang sesuai dan kirimkan lamaran Anda.</p>
                                    <a href="{{ route('careers.index') }}" class="inline-flex items-center gap-1.5 mt-3 px-3.5 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container transition-all">
                                        <span class="material-symbols-outlined text-[15px]">work</span>
                                        <span>Lihat Lowongan Kerja</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Profil Ringkas & Lowongan Terbuka (1 col) --}}
        <div class="flex flex-col gap-6">

            {{-- Kartu Ringkasan Profil --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20">
                    <h2 class="font-bold text-base text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">badge</span>
                        <span>Profil Karir Saya</span>
                    </h2>
                    <a href="{{ route('seeker.profile.show') }}" class="text-xs font-semibold text-primary hover:underline">
                        Kelola
                    </a>
                </div>

                <div class="flex flex-col gap-2 text-xs text-on-surface-variant">
                    <div class="flex justify-between py-1 border-b border-outline-variant/10">
                        <span class="font-medium">Nama</span>
                        <span class="font-bold text-on-surface">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/10">
                        <span class="font-medium">Email</span>
                        <span class="text-on-surface">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/10">
                        <span class="font-medium">No. Telepon</span>
                        <span class="text-on-surface">{{ $profile->phone ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-outline-variant/10">
                        <span class="font-medium">Pendidikan</span>
                        <span class="text-on-surface">{{ $profile->education ?? 'Belum diisi' }}</span>
                    </div>
                </div>

                <div class="mt-1 pt-3 border-t border-outline-variant/10 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs">
                        <span class="material-symbols-outlined text-[18px] {{ ($profile && $profile->cv_path) ? 'text-emerald-600' : 'text-amber-500' }}">
                            {{ ($profile && $profile->cv_path) ? 'check_circle' : 'pending' }}
                        </span>
                        <span class="font-semibold text-on-surface">
                            {{ ($profile && $profile->cv_path) ? 'CV Tersimpan' : 'CV Belum Ada' }}
                        </span>
                    </div>
                    @if ($profile && $profile->cv_path)
                        <a href="{{ route('seeker.profile.cv') }}" class="text-xs font-semibold text-primary hover:underline">
                            Unduh CV
                        </a>
                    @else
                        <a href="{{ route('seeker.profile.edit') }}" class="text-xs font-semibold text-amber-600 hover:underline">
                            Upload CV
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kartu Lowongan Rekomendasi --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20">
                    <h2 class="font-bold text-base text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">work</span>
                        <span>Lowongan Terbaru</span>
                    </h2>
                    <a href="{{ route('seeker.jobs') }}" class="text-xs font-semibold text-primary hover:underline">
                        Semua
                    </a>
                </div>

                <div class="flex flex-col divide-y divide-outline-variant/10">
                    @forelse ($availableJobs as $job)
                        <div class="py-2.5 flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('seeker.jobs.show', $job) }}" class="font-semibold text-xs text-on-surface hover:text-primary block truncate">
                                    {{ $job->title }}
                                </a>
                                <span class="text-[11px] text-on-surface-variant block">
                                    {{ $job->category }} • {{ $job->location }}
                                </span>
                            </div>
                            <a href="{{ route('seeker.jobs.apply', $job) }}" class="shrink-0 text-xs font-semibold text-primary hover:text-primary-container px-2 py-1 rounded bg-primary/5 hover:bg-primary/10">
                                Lamar
                            </a>
                        </div>
                    @empty
                        <div class="py-4 text-center text-xs text-on-surface-variant">
                            Tidak ada lowongan aktif saat ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Kartu Masukan & Feedback Layanan --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-[20px]">reviews</span>
                    <h2 class="font-bold text-base text-on-surface">Ulasan &amp; Masukan</h2>
                </div>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Bantu kami meningkatkan kualitas layanan rekrutmen dan seleksi dengan memberikan ulasan serta saran Anda.
                </p>
                <button type="button" onclick="openFeedbackModal()" class="w-full mt-1 px-4 py-2 rounded-lg bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface text-xs font-semibold flex items-center justify-center gap-2 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">rate_review</span>
                    <span>Beri Masukan Layanan</span>
                </button>
            </div>

        </div>

    </div>

</div>

{{-- Modal Masukan / Feedback --}}
<x-seeker-feedback-modal />
@endsection
