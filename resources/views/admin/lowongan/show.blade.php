@extends('layouts.admin')

@section('title', $jobListing->title . ' — Detail Lowongan Admin')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.lowongan.index') }}" class="hover:text-primary transition-colors">Lowongan Kerja</a>
                <span>/</span>
                <span class="text-on-surface font-semibold">Detail Lowongan</span>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                    {{ $jobListing->title }}
                </h1>
                @php
                    $statusBadge = match($jobListing->status) {
                        'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'draft'  => 'bg-amber-100 text-amber-800 border-amber-200',
                        'closed' => 'bg-rose-100 text-rose-800 border-rose-200',
                        default  => 'bg-slate-100 text-slate-800 border-slate-200',
                    };
                @endphp
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusBadge }}">
                    {{ $jobListing->status_label }}
                </span>
            </div>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Divisi {{ $jobListing->category }} &bull; {{ $jobListing->employment_type_label }} &bull; {{ $jobListing->location }}
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 flex-wrap shrink-0">
            <a href="{{ route('admin.lowongan.edit', $jobListing) }}" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs flex items-center gap-1.5 hover:bg-primary-container shadow-sm transition-all">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                <span>Edit Lowongan</span>
            </a>

            <form method="POST" action="{{ route('admin.lowongan.toggle', $jobListing) }}" class="inline m-0">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-semibold text-xs flex items-center gap-1.5 hover:bg-surface-variant border border-outline-variant/30 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">
                        {{ $jobListing->status === 'active' ? 'pause_circle' : 'play_circle' }}
                    </span>
                    <span>{{ $jobListing->status === 'active' ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}</span>
                </button>
            </form>

            <a href="{{ route('careers.show', $jobListing) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-on-surface font-semibold text-xs flex items-center gap-1.5 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Halaman Publik</span>
            </a>

            <a href="{{ route('admin.lowongan.index') }}" class="px-3.5 py-2 rounded-lg bg-surface-container-low text-on-surface font-semibold text-xs flex items-center gap-1.5 hover:bg-surface-container-high border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- Main 2-Column Info Grid --}}
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

        {{-- Right: Specifications & Metrics (1 Col) --}}
        <div class="space-y-6">
            {{-- Card Detail Spesifikasi --}}
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    <h2 class="text-base font-bold font-display text-on-surface">Informasi Rekrutmen</h2>
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
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Lokasi Penempatan</div>
                        <div class="font-semibold text-on-surface mt-0.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">location_on</span>
                            <span>{{ $jobListing->location }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Rentang Gaji Ditawarkan</div>
                        <div class="font-bold text-primary mt-0.5">
                            {{ $jobListing->salary_range ?? 'Kompetitif / Negosiasi' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Batas Waktu Lamaran</div>
                        <div class="font-semibold mt-0.5 flex items-center gap-1 {{ $jobListing->deadline->isPast() ? 'text-rose-600' : 'text-on-surface' }}">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span>{{ $jobListing->deadline->format('d F Y') }}</span>
                            @if ($jobListing->deadline->isPast())
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold ml-1">Lewat</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tanggal Dibuat</div>
                        <div class="text-on-surface-variant mt-0.5">{{ $jobListing->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            {{-- Card Pelamar Masuk Ringkas --}}
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20">
                    <span class="text-xs font-semibold uppercase text-on-surface-variant tracking-wider">Total Pelamar</span>
                    <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                </div>
                <div class="text-3xl font-bold font-display text-on-surface">
                    {{ $jobListing->applications_count }} <span class="text-sm font-normal text-on-surface-variant">berkas</span>
                </div>
                <p class="text-xs text-on-surface-variant">
                    Semua berkas lamaran yang diajukan kandidat untuk posisi ini.
                </p>
                <div class="pt-2">
                    <a href="{{ route('admin.lamaran.index', ['job_id' => $jobListing->id]) }}" class="w-full px-4 py-2 rounded-lg bg-surface-container-high text-primary font-semibold text-xs flex items-center justify-center gap-1.5 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                        <span>Buka di Modul Lamaran Masuk</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Bottom: Applicants Table for this Job Listing --}}
    <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-outline-variant/20">
            <div>
                <h3 class="text-base font-bold font-display text-on-surface">
                    Daftar Kandidat Pelamar Posisi Ini
                </h3>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Menampilkan seluruh riwayat pelamar yang mendaftar ke lowongan {{ $jobListing->title }}.
                </p>
            </div>
            <a href="{{ route('admin.lamaran.index', ['job_id' => $jobListing->id]) }}" class="text-xs font-semibold text-primary hover:underline">
                Lihat di Halaman Lamaran &rarr;
            </a>
        </div>

        {{-- Search & Filter Toolbar for Applicants --}}
        <form method="GET" action="{{ route('admin.lowongan.show', $jobListing) }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelamar, email, HP..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="status"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.lowongan.show', $jobListing) }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">Nama Pelamar</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3">Tanggal Melamar</th>
                        <th class="px-4 py-3">Status Seleksi</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-on-surface">{{ $app->name }}</div>
                                <div class="text-xs text-on-surface-variant">
                                    {{ $app->user ? 'Akun Terdaftar: ' . $app->user->email : 'Lamaran Arsip (Legacy)' }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-xs">
                                <div class="font-medium text-on-surface">{{ $app->email }}</div>
                                <div class="text-on-surface-variant">{{ $app->phone }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-on-surface-variant">
                                {{ $app->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3.5">
                                @php
                                    $badge = match ($app->status) {
                                        'pending'     => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'reviewed'    => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'shortlisted' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'interviewed' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'accepted'    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'rejected'    => 'bg-rose-100 text-rose-800 border-rose-200',
                                        default       => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badge }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('admin.lamaran.show', $app) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container-high text-primary hover:bg-surface-variant text-xs font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    <span>Tinjau</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-on-surface-variant text-sm">
                                Belum ada berkas lamaran yang masuk untuk lowongan kerja ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($applications->hasPages())
            <div class="pt-3 border-t border-outline-variant/20">
                {{ $applications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
