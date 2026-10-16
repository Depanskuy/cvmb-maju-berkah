@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Log Aktivitas &amp; Audit Trail Sistem
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Catatan kronologis seluruh interaksi pengguna, modifikasi lowongan kerja, alur seleksi berkas, dan pembaruan profil secara transparan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-surface-container-high text-xs font-semibold text-on-surface border border-outline-variant/30">
                <span class="material-symbols-outlined text-[16px] text-primary">security</span>
                <span>Audit Trail Aktif</span>
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-on-surface-variant uppercase">Total Aktivitas</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-primary uppercase">Aktivitas Admin</div>
            <div class="text-2xl font-bold font-display text-primary mt-1">{{ number_format($stats['admin']) }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-sky-700 uppercase">Aktivitas Pelamar</div>
            <div class="text-2xl font-bold font-display text-sky-600 mt-1">{{ number_format($stats['seeker']) }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-700 uppercase">Hari Ini</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ number_format($stats['today']) }}</div>
        </div>
    </div>

    {{-- Filters & Search Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.log.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas, pelaku, alamat IP..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="module"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Modul</option>
                <option value="Lowongan Kerja" {{ request('module') === 'Lowongan Kerja' ? 'selected' : '' }}>Lowongan Kerja</option>
                <option value="Lamaran Masuk" {{ request('module') === 'Lamaran Masuk' ? 'selected' : '' }}>Lamaran Masuk</option>
                <option value="Profil Pelamar" {{ request('module') === 'Profil Pelamar' ? 'selected' : '' }}>Profil Pelamar</option>
                <option value="Autentikasi" {{ request('module') === 'Autentikasi' ? 'selected' : '' }}>Autentikasi</option>
            </select>

            <select name="action"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Tipe Aksi</option>
                <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Tambah Data</option>
                <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Pembaruan</option>
                <option value="status_change" {{ request('action') === 'status_change' ? 'selected' : '' }}>Ubah Status</option>
                <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Hapus Data</option>
                <option value="withdraw" {{ request('action') === 'withdraw' ? 'selected' : '' }}>Pembatalan</option>
                <option value="auth" {{ request('action') === 'auth' ? 'selected' : '' }}>Autentikasi</option>
            </select>

            <select name="role"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Peran</option>
                <option value="Admin Perusahaan" {{ request('role') === 'Admin Perusahaan' ? 'selected' : '' }}>Admin Perusahaan</option>
                <option value="Pencari Kerja" {{ request('role') === 'Pencari Kerja' ? 'selected' : '' }}>Pencari Kerja</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'module', 'action', 'role']))
                <a href="{{ route('admin.log.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Activity Log Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
        <div class="p-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-base font-display text-on-surface">Riwayat Perubahan &amp; Aktivitas</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">Daftar rekaman kejadian yang tersimpan di sistem.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-primary/10 text-primary">
                {{ $logs->total() }} Log Ditemukan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Waktu Kejadian</th>
                        <th class="px-5 py-3.5">Pengguna / Pelaku</th>
                        <th class="px-5 py-3.5">Peran</th>
                        <th class="px-5 py-3.5">Modul</th>
                        <th class="px-5 py-3.5">Tipe Aksi</th>
                        <th class="px-5 py-3.5">Rincian Aktivitas</th>
                        <th class="px-5 py-3.5">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-xs">
                                <div class="font-semibold text-on-surface">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                <div class="text-[11px] text-on-surface-variant">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-on-surface">{{ $log->causer_name }}</div>
                                @if($log->user)
                                    <div class="text-[11px] text-on-surface-variant">{{ $log->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php
                                    $roleBadge = match($log->causer_role) {
                                        'Admin Perusahaan' => 'bg-primary/10 text-primary border-primary/20',
                                        'Pencari Kerja'    => 'bg-sky-100 text-sky-800 border-sky-200',
                                        default            => 'bg-surface-container-high text-on-surface border-outline-variant/30',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $roleBadge }}">
                                    {{ $log->causer_role }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface">
                                    {{ $log->module }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $log->action_badge }}">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-on-surface max-w-md">
                                <div class="leading-relaxed">{{ $log->description }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs text-on-surface-variant font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-on-surface-variant text-sm">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">manage_search</span>
                                Tidak ada log aktivitas yang cocok dengan kriteria filter Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="p-4 border-t border-outline-variant/20 bg-surface-container-low/30">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
