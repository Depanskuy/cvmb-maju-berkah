@extends('layouts.admin')

@section('title', 'Feedback & Evaluasi — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Feedback &amp; Evaluasi Layanan
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Saluran penampung ulasan, rating kepuasan, dan saran perbaikan dari pelamar kerja serta pengguna portal CV Maju Sukses.
            </p>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-on-surface-variant uppercase">Total Masukan</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-amber-700 uppercase">Kepuasan Rata-rata</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1 flex items-center gap-1">
                <span>{{ $stats['average_rating'] }}</span>
                <span class="text-sm font-normal text-on-surface-variant">/ 5.0</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-sky-700 uppercase">Perlu Ditinjau</div>
            <div class="text-2xl font-bold font-display text-sky-600 mt-1">{{ $stats['unread'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-700 uppercase">Telah Ditindaklanjuti</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['resolved'] }}</div>
        </div>
    </div>

    {{-- Filters & Search Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.feedback.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kategori, ulasan..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="rating"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Rating</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Bintang (Sangat Puas)</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Bintang (Puas)</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Bintang (Cukup)</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Bintang (Kurang)</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Bintang (Kecewa)</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'rating']))
                <a href="{{ route('admin.feedback.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Feedback List --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @forelse ($feedbacks as $fb)
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container-high text-on-surface">
                            {{ $fb->category }}
                        </span>
                        <div class="flex items-center text-amber-400">
                            @for ($i = 0; $i < $fb->rating; $i++)
                                <span class="material-symbols-outlined text-[18px]">star</span>
                            @endfor
                            @for ($i = $fb->rating; $i < 5; $i++)
                                <span class="material-symbols-outlined text-[18px] text-outline-variant/40">star</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-on-surface leading-relaxed italic">
                        "{{ $fb->message }}"
                    </p>
                </div>

                <div class="space-y-3 pt-3 border-t border-outline-variant/20">
                    <div class="flex items-center justify-between text-xs text-on-surface-variant">
                        <div>
                            <div class="font-bold text-on-surface">{{ $fb->name }}</div>
                            <div class="text-[11px]">{{ $fb->created_at ? $fb->created_at->format('d M Y, H:i') : '-' }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $fb->status === 'Baru' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $fb->status }}
                        </span>
                    </div>

                    {{-- Action Controls --}}
                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-outline-variant/15">
                        <form method="POST" action="{{ route('admin.feedback.status.update', $fb->id) }}" class="m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors {{ $fb->status === 'Baru' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-highest' }}">
                                <span class="material-symbols-outlined text-[14px]">{{ $fb->status === 'Baru' ? 'done_all' : 'undo' }}</span>
                                <span>{{ $fb->status === 'Baru' ? 'Tandai Selesai' : 'Tandai Baru' }}</span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.feedback.destroy', $fb->id) }}" onsubmit="return confirm('Hapus masukan dari {{ $fb->name }}? Tindakan ini tidak dapat dibatalkan.');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Masukan">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center bg-surface-container-lowest rounded-2xl border border-outline-variant/30 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">reviews</span>
                Tidak ada masukan/ulasan yang cocok dengan kriteria pencarian Anda.
            </div>
        @endforelse
    </div>

</div>
@endsection
