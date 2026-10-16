{{-- resources/views/components/seeker-topbar.blade.php --}}
@props(['title' => null])

@php
    $currentRoute = request()->route()?->getName() ?? '';
    $breadcrumbPage = match(true) {
        str_contains($currentRoute, 'dashboard')        => 'Dashboard',
        str_contains($currentRoute, 'applications.show') => 'Detail Lamaran',
        str_contains($currentRoute, 'applications')     => 'Lamaran Saya',
        str_contains($currentRoute, 'jobs.apply')        => 'Formulir Lamaran',
        str_contains($currentRoute, 'jobs.show')         => 'Detail Lowongan',
        str_contains($currentRoute, 'jobs')              => 'Jelajahi Lowongan',
        str_contains($currentRoute, 'profile.edit')      => 'Edit Profil',
        str_contains($currentRoute, 'profile.create')    => 'Buat Profil',
        str_contains($currentRoute, 'profile')           => 'Profil Saya',
        default                                          => $title ?? 'Dashboard',
    };

    $user = auth()->user();
    $applications = $user ? $user->applications()->with('jobListing')->latest()->take(5)->get() : collect();
    $activeCount = $user ? $user->applications()->whereNull('withdrawn_at')->count() : 0;
@endphp

<header class="sticky top-0 z-30 h-16 w-full bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex items-center justify-between px-4 sm:px-6 shrink-0">

    {{-- Kiri: hamburger button & breadcrumb dinamis (Pelamar › [Halaman]) --}}
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleSeekerSidebar()" class="lg:hidden p-2 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors" title="Buka Menu">
            <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>

        <nav class="flex items-center gap-2 font-display text-sm sm:text-base">
            <a href="{{ route('seeker.dashboard') }}" class="text-on-surface-variant hover:text-primary transition-colors font-semibold">
                Pelamar
            </a>
            <span class="text-outline-variant font-bold text-sm">›</span>
            <span class="text-primary font-bold tracking-tight">
                {{ $breadcrumbPage }}
            </span>
        </nav>
    </div>

    {{-- Kanan: notifikasi fungsional & profil --}}
    <div class="flex items-center gap-2 sm:gap-3">

        {{-- Functional Notification Bell for Seeker --}}
        <div class="relative">
            <button type="button" id="seeker-notif-btn" onclick="toggleSeekerNotif()"
                    class="relative p-2 rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors cursor-pointer"
                    title="Notifikasi Lamaran">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                @if ($activeCount > 0)
                    <span id="seeker-notif-badge" class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white text-[10px] flex items-center justify-center font-bold shadow-xs">
                        {{ $activeCount > 9 ? '9+' : $activeCount }}
                    </span>
                @endif
            </button>

            {{-- Dropdown Notifikasi Pelamar --}}
            <div id="seeker-notif-dropdown" class="hidden absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-xl z-50 overflow-hidden transform transition-all duration-200">
                <div class="p-4 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-low/50">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-on-surface font-display">Status Lamaran</span>
                        <span id="seeker-notif-count-tag" class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary">
                            {{ $activeCount }} Lamaran
                        </span>
                    </div>
                    <button type="button" onclick="clearSeekerNotifBadge()" class="text-[11px] font-semibold text-primary hover:underline cursor-pointer">
                        Tandai Sudah Dibaca
                    </button>
                </div>

                <div class="max-h-80 overflow-y-auto divide-y divide-outline-variant/10 text-xs">
                    @forelse ($applications as $app)
                        <a href="{{ route('seeker.applications.show', $app) }}" onclick="clearSeekerNotifBadge()" class="p-3.5 flex items-start gap-3 hover:bg-surface-container-low/60 transition-colors block">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[16px]">work</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-on-surface truncate">{{ $app->jobListing->title ?? 'Posisi Pekerjaan' }}</div>
                                <div class="text-on-surface-variant mt-0.5">
                                    Status saat ini: <strong class="text-primary">{{ $app->status_label }}</strong>
                                </div>
                                @if ($app->review_note)
                                    <div class="text-[11px] text-on-surface bg-surface-container-low p-1.5 rounded mt-1.5 italic line-clamp-2">
                                        "{{ $app->review_note }}"
                                    </div>
                                @endif
                                <div class="text-[10px] text-outline mt-1">{{ $app->updated_at->diffForHumans() }}</div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-3xl text-outline mb-1">inbox</span>
                            <div class="font-semibold text-xs">Belum Ada Riwayat Lamaran</div>
                            <div class="text-[11px] text-outline mt-0.5">Anda belum mengajukan berkas lamaran pekerjaan.</div>
                        </div>
                    @endforelse
                </div>

                <div class="p-3 border-t border-outline-variant/20 bg-surface-container-low/30 text-center">
                    <a href="{{ route('seeker.applications') }}" onclick="clearSeekerNotifBadge()" class="text-xs font-semibold text-primary hover:underline">
                        Lihat Seluruh Lamaran Saya &rarr;
                    </a>
                </div>
            </div>
        </div>

        {{-- User Avatar / Profil --}}
        <a href="{{ route('seeker.profile.show') }}" class="flex items-center gap-2 pl-2 border-l border-surface-container-high text-decoration-none" title="Lihat Profil">
            <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs shadow-sm">
                {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
            </div>
            <span class="hidden md:inline font-semibold text-xs text-on-surface truncate max-w-[140px]">
                {{ auth()->user()->name ?? 'Pelamar' }}
            </span>
        </a>
    </div>
</header>

<script>
    function toggleSeekerNotif() {
        const dd = document.getElementById('seeker-notif-dropdown');
        if (dd) {
            dd.classList.toggle('hidden');
            // Menghilangkan notif saat dibuka
            clearSeekerNotifBadge();
        }
    }

    function clearSeekerNotifBadge() {
        const badge = document.getElementById('seeker-notif-badge');
        if (badge) {
            badge.remove();
        }
        const tag = document.getElementById('seeker-notif-count-tag');
        if (tag) {
            tag.textContent = '0 Baru';
            tag.classList.remove('bg-primary/10', 'text-primary');
            tag.classList.add('bg-surface-container-high', 'text-on-surface-variant');
        }
        localStorage.setItem('seeker_notif_cleared_' + {{ auth()->id() ?? 0 }}, Date.now());
    }

    // Auto-hilang badge jika sudah pernah dibuka di browser ini dalam sesi sekarang
    document.addEventListener('DOMContentLoaded', function () {
        const cleared = localStorage.getItem('seeker_notif_cleared_' + {{ auth()->id() ?? 0 }});
        if (cleared) {
            const badge = document.getElementById('seeker-notif-badge');
            if (badge) {
                badge.remove();
            }
            const tag = document.getElementById('seeker-notif-count-tag');
            if (tag) {
                tag.textContent = '0 Baru';
            }
        }
    });

    // Close on click outside
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('seeker-notif-btn');
        const dd = document.getElementById('seeker-notif-dropdown');
        if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });
</script>
