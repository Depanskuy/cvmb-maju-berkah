@extends('layouts.seeker')

@section('title', 'Jelajahi Lowongan Kerja — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Jelajahi Lowongan Kerja
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Temukan peluang karir terbaik sesuai kompetensi dan aspirasi profesional Anda di CV Maju Sukses.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('seeker.applications') }}" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm flex items-center gap-2 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[18px]">assignment</span>
                <span>Lamaran Saya</span>
            </a>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('seeker.jobs') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[220px] max-w-md">
                <span class="material-symbols-outlined absolute left-3.5 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari posisi, divisi, kota..."
                       class="w-full pl-10 pr-3.5 py-2.5 bg-surface-container-low rounded-xl text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <select name="category"
                    class="px-3.5 py-2.5 bg-surface-container-low rounded-xl text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->category }}" {{ request('category') === $cat->category ? 'selected' : '' }}>
                        {{ $cat->category }} ({{ $cat->count }})
                    </option>
                @endforeach
            </select>

            <select name="type"
                    class="px-3.5 py-2.5 bg-surface-container-low rounded-xl text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Tipe Kerja</option>
                <option value="full_time" {{ request('type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                <option value="part_time" {{ request('type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                <option value="internship" {{ request('type') === 'internship' ? 'selected' : '' }}>Magang</option>
                <option value="contract" {{ request('type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl bg-primary text-on-primary font-semibold text-xs hover:bg-primary-container shadow-xs transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['q', 'category', 'type']))
                <a href="{{ route('seeker.jobs') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Job Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($jobs as $job)
            @php $isApplied = in_array($job->id, $appliedJobIds); @endphp
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col justify-between hover:border-primary/40 hover:shadow-md transition-all">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary/10 text-primary">
                            {{ $job->category }}
                        </span>
                        @if ($isApplied)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                <span>Sudah Dilamar</span>
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-surface-container-high text-on-surface-variant">
                                {{ $job->employment_type_label }}
                            </span>
                        @endif
                    </div>

                    <h2 class="text-lg font-bold font-display text-on-surface line-clamp-1 hover:text-primary transition-colors">
                        <a href="{{ route('seeker.jobs.show', $job) }}">{{ $job->title }}</a>
                    </h2>

                    <p class="text-xs text-on-surface-variant line-clamp-2 leading-relaxed">
                        {{ $job->description }}
                    </p>

                    <div class="pt-2 space-y-1.5 text-xs text-on-surface-variant">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">location_on</span>
                            <span>{{ $job->location }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">payments</span>
                            <span class="font-medium text-on-surface">{{ $job->salary_range ?? 'Kompetitif' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">event</span>
                            <span>Deadline: {{ $job->deadline->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-5 mt-4 border-t border-outline-variant/20 flex items-center justify-between gap-2">
                    <a href="{{ route('seeker.jobs.show', $job) }}" class="text-xs font-semibold text-primary hover:underline">
                        Lihat Rincian &rarr;
                    </a>
                    @if ($isApplied)
                        <a href="{{ route('seeker.applications') }}" class="px-3 py-1.5 rounded-lg bg-surface-container-high text-on-surface text-xs font-semibold hover:bg-surface-variant transition-colors">
                            Cek Status
                        </a>
                    @else
                        <a href="{{ route('seeker.jobs.apply', $job) }}" class="px-3.5 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container shadow-xs transition-all">
                            Lamar Sekarang
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 rounded-2xl bg-surface-container-lowest border border-dashed border-outline-variant/50 text-center">
                <span class="material-symbols-outlined text-4xl text-outline mb-2">work_off</span>
                <h3 class="font-bold text-base text-on-surface">Tidak ada lowongan ditemukan</h3>
                <p class="text-xs text-on-surface-variant mt-1">Coba ubah kata kunci atau bersihkan filter pencarian Anda.</p>
                <a href="{{ route('seeker.jobs') }}" class="mt-4 inline-block px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-primary hover:bg-surface-variant">
                    Lihat Semua Lowongan
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pt-2">
        {{ $jobs->links() }}
    </div>

</div>
@endsection
