@extends('layouts.admin')

@section('title', 'Dashboard — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Welcome banner --}}
    <div class="p-6 rounded-2xl bg-gradient-to-r from-primary to-primary-container text-on-primary shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-white">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-white/80 text-sm mt-1">
                Panel Administrasi CV Maju Sukses — Kelola lowongan karir dan lamaran masuk secara efisien.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.lowongan.create') }}" class="px-4 py-2.5 rounded-lg bg-secondary-container text-on-primary-fixed font-semibold text-sm flex items-center gap-2 hover:brightness-110 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Buat Lowongan</span>
            </a>
            <a href="{{ route('admin.lamaran.index') }}" class="px-4 py-2.5 rounded-lg bg-white/10 text-white font-semibold text-sm flex items-center gap-2 hover:bg-white/20 transition-all">
                <span class="material-symbols-outlined text-[18px]">assignment_ind</span>
                <span>Lamaran Masuk</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Lowongan --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Lowongan</span>
                <span class="material-symbols-outlined text-primary text-[22px]">work</span>
            </div>
            <div class="text-3xl font-bold font-display text-on-surface mt-2">
                {{ $stats['total_jobs'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                {{ $stats['active_jobs'] }} posisi aktif saat ini
            </div>
        </div>

        {{-- Lowongan Aktif --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Lowongan Aktif</span>
                <span class="material-symbols-outlined text-emerald-600 text-[22px]">check_circle</span>
            </div>
            <div class="text-3xl font-bold font-display text-emerald-600 mt-2">
                {{ $stats['active_jobs'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Terbuka untuk dilamar publik
            </div>
        </div>

        {{-- Total Lamaran --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Lamaran</span>
                <span class="material-symbols-outlined text-primary-container text-[22px]">assignment_ind</span>
            </div>
            <div class="text-3xl font-bold font-display text-on-surface mt-2">
                {{ $stats['total_apps'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Seluruh berkas masuk
            </div>
        </div>

        {{-- Menunggu Review --}}
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col">
            <div class="flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-semibold uppercase tracking-wider">Perlu Review</span>
                <span class="material-symbols-outlined text-amber-600 text-[22px]">pending_actions</span>
            </div>
            <div class="text-3xl font-bold font-display text-amber-600 mt-2">
                {{ $stats['pending_apps'] }}
            </div>
            <div class="text-xs text-on-surface-variant/80 mt-1">
                Menunggu peninjauan HR
            </div>
        </div>
    </div>

    {{-- Main Grid: Recent Applications & Active Jobs --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Recent Applications (2 cols) --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-outline-variant/20 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-base text-on-surface">Lamaran Masuk Terbaru</h2>
                    <p class="text-xs text-on-surface-variant">Kandidat yang baru mengirimkan berkas lamaran</p>
                </div>
                <a href="{{ route('admin.lamaran.index') }}" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">Pelamar</th>
                            <th class="px-5 py-3">Posisi Dilamar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                        @forelse ($recentApplications as $app)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-on-surface">{{ $app->applicant_name }}</div>
                                    <div class="text-xs text-on-surface-variant">{{ $app->applicant_email }}</div>
                                    @if ($app->user_id)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-primary bg-primary/10 px-1.5 py-0.5 rounded mt-0.5">
                                            Akun Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded mt-0.5">
                                            Publik (Legacy)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium">{{ $app->jobListing->title ?? 'Posisi Tidak Ada' }}</div>
                                    <div class="text-xs text-on-surface-variant">{{ $app->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-5 py-3.5">
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
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.lamaran.show', $app) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-primary/10 text-primary text-xs font-semibold hover:bg-primary hover:text-white transition-colors">
                                        <span>Review</span>
                                        <span class="material-symbols-outlined text-[14px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-on-surface-variant text-sm">
                                    Belum ada lamaran masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Active Jobs (1 col) --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5 flex flex-col">
            <div class="flex items-center justify-between pb-4 border-b border-outline-variant/20">
                <div>
                    <h2 class="font-bold text-base text-on-surface">Lowongan Aktif</h2>
                    <p class="text-xs text-on-surface-variant">Posisi yang sedang dibuka</p>
                </div>
                <a href="{{ route('admin.lowongan.index') }}" class="text-xs font-semibold text-primary hover:underline">
                    Kelola
                </a>
            </div>

            <div class="flex flex-col divide-y divide-outline-variant/15 mt-2">
                @forelse ($activeJobListings as $job)
                    <div class="py-3 flex flex-col gap-1">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.lowongan.edit', $job) }}" class="font-semibold text-sm text-on-surface hover:text-primary">
                                {{ $job->title }}
                            </a>
                            <span class="text-xs font-semibold bg-primary/10 text-primary px-2 py-0.5 rounded-full">
                                {{ $job->applications_count }} pelamar
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                            <span>{{ $job->category }}</span>
                            <span>•</span>
                            <span>Deadline: {{ $job->deadline->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-on-surface-variant">
                        Tidak ada lowongan aktif.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
