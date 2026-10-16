{{-- resources/views/components/topbar.blade.php --}}
@props(['title' => null])

@php
    $currentRoute = request()->route()?->getName() ?? '';
    $breadcrumbPage = match(true) {
        str_contains($currentRoute, 'dashboard')      => 'Dashboard',
        str_contains($currentRoute, 'lowongan.create') => 'Tambah Lowongan',
        str_contains($currentRoute, 'lowongan.edit')   => 'Edit Lowongan',
        str_contains($currentRoute, 'lowongan.show')   => 'Detail Lowongan',
        str_contains($currentRoute, 'lowongan')        => 'Lowongan Kerja',
        str_contains($currentRoute, 'lamaran.show')    => 'Review Lamaran',
        str_contains($currentRoute, 'lamaran')         => 'Lamaran Masuk',
        str_contains($currentRoute, 'kerja-sama')      => 'Pengajuan Kerja Sama',
        str_contains($currentRoute, 'feedback')        => 'Feedback',
        str_contains($currentRoute, 'project')         => 'Project',
        str_contains($currentRoute, 'log')             => 'Log Aktivitas',
        str_contains($currentRoute, 'pengaturan')      => 'Pengaturan',
        default                                        => $title ?? 'Dashboard',
    };

    $pendingCount = \App\Models\Application::whereNull('withdrawn_at')
        ->where('status', \App\Models\Application::STATUS_PENDING)
        ->count();

    $recentPending = \App\Models\Application::with('jobListing')
        ->whereNull('withdrawn_at')
        ->where('status', \App\Models\Application::STATUS_PENDING)
        ->latest()
        ->take(5)
        ->get();
@endphp

<header class="sticky top-0 z-30 h-16 w-full bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex items-center justify-between px-4 sm:px-6 shrink-0">

    {{-- Kiri: hamburger button & breadcrumb dinamis (Admin › [Halaman]) --}}
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors" title="Buka Menu">
            <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>

        <nav class="flex items-center gap-2 font-display text-sm sm:text-base">
            <a href="{{ route('admin.dashboard') }}" class="text-on-surface-variant hover:text-primary transition-colors font-semibold">
                Admin
            </a>
            <span class="text-outline-variant font-bold text-sm">›</span>
            <span class="text-primary font-bold tracking-tight">
                {{ $breadcrumbPage }}
            </span>
        </nav>
    </div>

    {{-- Kanan: notifikasi dropdown fungsional & avatar profil --}}
    <div class="flex items-center gap-2 sm:gap-3">

        {{-- Functional Notification Bell & Popover --}}
        <div class="relative">
            <button type="button" id="admin-notif-btn" onclick="toggleAdminNotif()"
                    class="relative p-2 rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors cursor-pointer"
                    title="Notifikasi Masuk">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                @if ($pendingCount > 0)
                    <span id="admin-notif-badge" class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white text-[10px] flex items-center justify-center font-bold shadow-xs">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                @endif
            </button>

            {{-- Dropdown Panel Notifikasi --}}
            <div id="admin-notif-dropdown" class="hidden absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-xl z-50 overflow-hidden transform transition-all duration-200">
                <div class="p-4 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-low/50">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-on-surface font-display">Notifikasi Masuk</span>
                        <span id="admin-notif-count-tag" class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary">
                            {{ $pendingCount }} Baru
                        </span>
                    </div>
                    <button type="button" onclick="clearAdminNotifBadge()" class="text-[11px] font-semibold text-primary hover:underline cursor-pointer">
                        Tandai Sudah Dibaca
                    </button>
                </div>

                <div id="admin-notif-list" class="max-h-80 overflow-y-auto divide-y divide-outline-variant/10 text-xs">
                    @forelse ($recentPending as $item)
                        <a href="{{ route('admin.lamaran.show', $item) }}" onclick="clearAdminNotifBadge()" class="p-3.5 flex items-start gap-3 hover:bg-surface-container-low/60 transition-colors block">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[16px]">assignment_ind</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-on-surface truncate">{{ $item->applicant_name }}</div>
                                <div class="text-on-surface-variant truncate">Melamar: {{ $item->jobListing->title ?? 'Lowongan Kerja' }}</div>
                                <div class="text-[10px] text-outline mt-1">{{ $item->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0 mt-2"></span>
                        </a>
                    @empty
                        <div class="p-6 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-3xl text-outline mb-1">done_all</span>
                            <div class="font-semibold text-xs">Tidak Ada Lamaran Tertunda</div>
                            <div class="text-[11px] text-outline mt-0.5">Semua berkas pelamar telah Anda tinjau.</div>
                        </div>
                    @endforelse
                </div>

                <div class="p-3 border-t border-outline-variant/20 bg-surface-container-low/30 text-center">
                    <a href="{{ route('admin.lamaran.index') }}" onclick="clearAdminNotifBadge()" class="text-xs font-semibold text-primary hover:underline">
                        Lihat Seluruh Lamaran Masuk &rarr;
                    </a>
                </div>
            </div>
        </div>

        {{-- User Avatar / Profil --}}
        <div class="flex items-center gap-2 pl-2 border-l border-surface-container-high">
            <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs shadow-sm">
                <span class="material-symbols-outlined text-[20px]">person</span>
            </div>
            <span class="hidden md:inline font-semibold text-xs text-on-surface truncate max-w-[140px]">
                {{ auth()->user()->name ?? 'Administrator' }}
            </span>
        </div>
    </div>
</header>

<script>
    function toggleAdminNotif() {
        const dd = document.getElementById('admin-notif-dropdown');
        if (dd) {
            dd.classList.toggle('hidden');
            // Menghilangkan notif saat dibuka
            clearAdminNotifBadge();
        }
    }

    function clearAdminNotifBadge() {
        const badge = document.getElementById('admin-notif-badge');
        if (badge) {
            badge.remove();
        }
        const tag = document.getElementById('admin-notif-count-tag');
        if (tag) {
            tag.textContent = '0 Baru';
            tag.classList.remove('bg-primary/10', 'text-primary');
            tag.classList.add('bg-surface-container-high', 'text-on-surface-variant');
        }
        localStorage.setItem('admin_notif_cleared_' + {{ auth()->id() ?? 0 }}, Date.now());
    }

    // Auto-hilang badge jika sudah pernah dibuka di browser ini dalam sesi sekarang
    document.addEventListener('DOMContentLoaded', function () {
        const cleared = localStorage.getItem('admin_notif_cleared_' + {{ auth()->id() ?? 0 }});
        if (cleared) {
            const badge = document.getElementById('admin-notif-badge');
            if (badge) {
                badge.remove();
            }
            const tag = document.getElementById('admin-notif-count-tag');
            if (tag) {
                tag.textContent = '0 Baru';
            }
        }
    });

    // Close on click outside
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('admin-notif-btn');
        const dd = document.getElementById('admin-notif-dropdown');
        if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });
</script>