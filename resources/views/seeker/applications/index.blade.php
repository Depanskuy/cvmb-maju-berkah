@extends('layouts.seeker')

@section('title', 'Lamaran Saya — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success') || session('apply_success') || session('success_feedback'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') ?? session('apply_success') ?? session('success_feedback') }}</span>
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
                Daftar Lamaran Pekerjaan Saya
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Pantau riwayat berkas, jadwal wawancara, dan keputusan tim rekrutmen CV Maju Sukses secara real-time.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <button type="button" onclick="openFeedbackModal()" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm flex items-center gap-2 hover:bg-surface-container-highest shadow-sm transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">rate_review</span>
                <span>Beri Masukan Layanan</span>
            </button>
            <a href="{{ route('seeker.jobs') }}" class="px-4 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">travel_explore</span>
                <span>Cari Lowongan Lain</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards (Identik dengan Admin) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-on-surface-variant uppercase">Total Lamaran</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-amber-700 uppercase">Menunggu Review</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $stats['pending'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-sky-700 uppercase">Proses Seleksi</div>
            <div class="text-2xl font-bold font-display text-sky-600 mt-1">{{ $stats['processing'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-700 uppercase">Diterima</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['accepted'] }}</div>
        </div>
    </div>

    {{-- Filters & Search Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('seeker.applications') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi lowongan, divisi, kota..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="status"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses / Interview</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima Bekerja</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Berkas Ditolak</option>
                <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Dibatalkan Saya</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('seeker.applications') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Applications Card / Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Posisi Lowongan</th>
                        <th class="px-5 py-3.5">Atas Nama</th>
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
                                <div class="font-bold text-on-surface">
                                    {{ $app->jobListing->title ?? 'Posisi Tidak Tersedia' }}
                                </div>
                                <div class="text-xs text-on-surface-variant">
                                    Divisi {{ $app->jobListing->category ?? '-' }} • {{ $app->jobListing->location ?? '-' }}
                                </div>
                                @if ($app->review_note)
                                    <div class="mt-2 text-xs text-primary bg-primary/5 p-2 rounded-lg border border-primary/15 max-w-md">
                                        <div class="font-bold text-[11px] uppercase tracking-wider flex items-center gap-1 mb-0.5">
                                            <span class="material-symbols-outlined text-[14px]">feedback</span>
                                            <span>Catatan HR CV Maju Berkah:</span>
                                        </div>
                                        <p class="text-on-surface/90 m-0">{{ $app->review_note }}</p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <div class="font-semibold text-on-surface">{{ $app->applicant_name }}</div>
                                <div class="text-on-surface-variant">{{ $app->applicant_email }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs text-on-surface-variant whitespace-nowrap">
                                <div>{{ $app->created_at->format('d M Y') }}</div>
                                <div class="text-[11px]">{{ $app->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($app->cv_path)
                                    <a href="{{ route('seeker.applications.cv', $app) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-surface-container-high text-xs font-semibold text-primary hover:bg-primary hover:text-white transition-colors" title="Unduh CV">
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
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('seeker.applications.show', $app) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container transition-all">
                                        <span>Detail</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>

                                    @if ($app->canBeWithdrawn())
                                        <form method="POST" action="{{ route('seeker.applications.withdraw', $app) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan lamaran ini? Tindakan ini tidak dapat dibatalkan.');" class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors" title="Batalkan Lamaran">
                                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant">
                                <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-2 text-outline">
                                    <span class="material-symbols-outlined text-[24px]">inbox</span>
                                </div>
                                <div class="font-semibold text-sm text-on-surface">Tidak ada data lamaran</div>
                                <p class="text-xs text-on-surface-variant mt-0.5">Anda belum mengajukan lamaran untuk kriteria filter ini.</p>
                                <a href="{{ route('careers.index') }}" class="inline-flex items-center gap-1.5 mt-3 px-3.5 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container transition-all">
                                    <span class="material-symbols-outlined text-[15px]">work</span>
                                    <span>Jelajahi Lowongan</span>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant/20 bg-surface-container-lowest">
            {{ $applications->links() }}
        </div>
    </div>

</div>

{{-- Modal Masukan / Feedback --}}
<x-seeker-feedback-modal />
@endsection
