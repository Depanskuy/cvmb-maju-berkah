@extends('layouts.admin')

@section('title', 'Lamaran Masuk — Admin CV Maju Sukses')

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

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Manajemen Lamaran Masuk
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Tinjau berkas CV pelamar, perbarui tahapan seleksi, dan berikan feedback kepada kandidat.
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-[11px] font-semibold text-on-surface-variant uppercase">Total Masuk</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-[11px] font-semibold text-amber-700 uppercase">Menunggu</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $stats['pending'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-[11px] font-semibold text-sky-700 uppercase">Diproses / Interv.</div>
            <div class="text-2xl font-bold font-display text-sky-600 mt-1">{{ $stats['processing'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-[11px] font-semibold text-emerald-700 uppercase">Diterima</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['accepted'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-[11px] font-semibold text-rose-700 uppercase">Ditolak</div>
            <div class="text-2xl font-bold font-display text-rose-600 mt-1">{{ $stats['rejected'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-600 uppercase">Dibatalkan</div>
            <div class="text-2xl font-bold font-display text-slate-600 mt-1">{{ $stats['withdrawn'] }}</div>
        </div>
    </div>

    {{-- Filters Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.lamaran.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, nomor HP..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="job_id"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20 max-w-[220px]">
                <option value="">Semua Posisi Lowongan</option>
                @foreach ($jobListings as $j)
                    <option value="{{ $j->id }}" {{ request('job_id') == $j->id ? 'selected' : '' }}>
                        {{ $j->title }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Dibatalkan Pelamar</option>
            </select>

            <button type="submit" class="px-3 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'job_id', 'status']))
                <a href="{{ route('admin.lamaran.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Applications Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Pelamar</th>
                        <th class="px-5 py-3.5">Posisi Dilamar</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">CV</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-on-surface">{{ $app->applicant_name }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $app->applicant_email }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $app->applicant_phone ?? '-' }}</div>
                                @if ($app->user_id)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-800 bg-emerald-100 px-1.5 py-0.5 rounded mt-1">
                                        <span class="material-symbols-outlined text-[12px]">verified</span>
                                        <span>Akun Terverifikasi</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded mt-1">
                                        <span>Publik (Legacy)</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold">{{ $app->jobListing->title ?? 'Posisi Terhapus' }}</div>
                                <div class="text-xs text-on-surface-variant">Divisi {{ $app->jobListing->category ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs text-on-surface-variant">
                                <div>{{ $app->created_at->format('d M Y') }}</div>
                                <div class="text-[11px]">{{ $app->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($app->cv_path)
                                    <a href="{{ route('admin.lamaran.cv', $app) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-surface-container-high text-xs font-semibold text-primary hover:bg-primary hover:text-white transition-colors" title="Unduh Berkas CV">
                                        <span class="material-symbols-outlined text-[15px]">download</span>
                                        <span>CV.pdf</span>
                                    </a>
                                @else
                                    <span class="text-xs text-on-surface-variant italic">-</span>
                                @endif
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
                                <a href="{{ route('admin.lamaran.show', $app) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container transition-all">
                                    <span>Review</span>
                                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant text-sm">
                                Tidak ada data lamaran yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-outline-variant/20 bg-surface-container-lowest">
            {{ $applications->links() }}
        </div>
    </div>

</div>
@endsection
