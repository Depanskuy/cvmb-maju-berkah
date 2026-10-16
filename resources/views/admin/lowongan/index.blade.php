@extends('layouts.admin')

@section('title', 'Manajemen Lowongan Kerja — Admin CV Maju Sukses')

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

    @if (session('info'))
        <div class="p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sky-600">info</span>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Manajemen Lowongan Kerja
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Kelola status, jadwal publikasi, dan kualifikasi rekrutmen perusahaan.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.lowongan.create') }}" class="px-4 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Lowongan Baru</span>
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-on-surface-variant uppercase">Total Lowongan</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-700 uppercase">Aktif (Buka)</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['active'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-amber-700 uppercase">Draf</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $stats['draft'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-rose-700 uppercase">Ditutup</div>
            <div class="text-2xl font-bold font-display text-rose-600 mt-1">{{ $stats['closed'] }}</div>
        </div>
    </div>

    {{-- Filters & Search Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.lowongan.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi, divisi, kota..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="status"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
            </select>

            <select name="type"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Tipe</option>
                <option value="full_time" {{ request('type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                <option value="part_time" {{ request('type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                <option value="internship" {{ request('type') === 'internship' ? 'selected' : '' }}>Magang</option>
                <option value="contract" {{ request('type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
            </select>

            <button type="submit" class="px-3 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'status', 'type']))
                <a href="{{ route('admin.lowongan.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Job Listings Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Posisi &amp; Kategori</th>
                        <th class="px-5 py-3.5">Tipe &amp; Lokasi</th>
                        <th class="px-5 py-3.5">Deadline</th>
                        <th class="px-5 py-3.5">Pelamar</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                    @forelse ($jobs as $job)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.lowongan.show', $job) }}" class="font-bold text-on-surface hover:text-primary transition-colors">
                                    {{ $job->title }}
                                </a>
                                <div class="text-xs text-on-surface-variant">Divisi {{ $job->category }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-xs">{{ $job->employment_type_label }}</div>
                                <div class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[13px]">location_on</span>
                                    <span>{{ $job->location }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <span class="{{ $job->deadline->isPast() ? 'text-rose-600 font-semibold' : 'text-on-surface' }}">
                                    {{ $job->deadline->format('d M Y') }}
                                </span>
                                @if($job->deadline->isPast())
                                    <span class="block text-[10px] text-rose-600">Expired</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.lamaran.index', ['job_id' => $job->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                                    <span>{{ $job->applications_count }}</span>
                                    <span class="text-[10px]">berkas</span>
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusBadge = match($job->status) {
                                        'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'draft'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'closed' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        default  => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusBadge }}">
                                    {{ $job->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Detail --}}
                                    <a href="{{ route('admin.lowongan.show', $job) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors" title="Lihat Detail Lowongan">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>

                                    {{-- Toggle Status --}}
                                    <form method="POST" action="{{ route('admin.lowongan.toggle', $job) }}" class="inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors cursor-pointer" title="{{ $job->status === 'active' ? 'Tutup Lowongan' : 'Aktifkan Lowongan' }}">
                                            <span class="material-symbols-outlined text-[18px]">
                                                {{ $job->status === 'active' ? 'pause_circle' : 'play_circle' }}
                                            </span>
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.lowongan.edit', $job) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors" title="Edit Lowongan">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>

                                    {{-- View Public --}}
                                    <a href="{{ route('careers.show', $job) }}" target="_blank" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors" title="Lihat di Web Publik">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.lowongan.destroy', $job) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Lowongan">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant text-sm">
                                Tidak ada data lowongan yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-outline-variant/20 bg-surface-container-lowest">
            {{ $jobs->links() }}
        </div>
    </div>

</div>
@endsection
