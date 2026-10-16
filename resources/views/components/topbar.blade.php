{{-- resources/views/components/topbar.blade.php --}}
@props(['title' => 'Dashboard'])

<header class="fixed top-0 left-[260px] right-0 h-16 bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-space-lg">

    {{-- Kiri: breadcrumb --}}
    <div class="flex items-center gap-space-md">
        <button type="button" class="p-space-xs rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>
        <nav class="hidden sm:flex items-center gap-space-xs font-title-md text-title-md text-on-surface-variant">
            <span class="hover:text-on-surface cursor-pointer">Admin</span>
            <span class="material-symbols-outlined text-[16px] text-outline-variant">chevron_right</span>
            <span class="text-primary font-semibold">{{ $title }}</span>
        </nav>
    </div>

    {{-- Tengah: search --}}
    <div class="flex-1 max-w-[400px] mx-space-lg hidden md:block">
        <div class="relative flex items-center">
            <span class="material-symbols-outlined absolute left-3 text-outline text-[18px]">search</span>
            <input type="text" placeholder="Cari modul, pelamar, data..."
                   class="w-full pl-9 pr-14 py-2 bg-surface-container-low rounded-lg text-body-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            <kbd class="absolute right-2.5 px-1.5 py-0.5 rounded bg-surface-container-lowest text-label-sm text-on-surface-variant shadow-sm border border-outline-variant/30">Ctrl+K</kbd>
        </div>
    </div>

    {{-- Kanan: status, notif, avatar --}}
    <div class="flex items-center gap-space-md">
        <div class="hidden lg:flex items-center gap-space-xs px-space-sm py-1 rounded-full bg-surface-container-high">
            <span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse"></span>
            <span class="font-label-sm text-label-sm text-on-surface">Sistem Online</span>
        </div>

        <button type="button" class="relative p-space-xs rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[22px]">notifications</span>
            <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-error text-on-error font-label-sm text-[10px] flex items-center justify-center font-bold">3</span>
        </button>

        <div class="flex items-center gap-space-sm pl-space-xs border-l border-surface-container-high">
            <div class="relative">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center cursor-pointer">
                    <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                </div>
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-secondary-container ring-2 ring-surface-container-lowest"></span>
            </div>
        </div>
    </div>
</header>