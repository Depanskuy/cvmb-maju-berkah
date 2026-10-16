{{-- resources/views/components/sidebar.blade.php --}}
@php
    $menu = [
        ['route' => 'admin.dashboard',        'match' => 'admin.dashboard',        'icon' => 'dashboard',      'label' => 'Dashboard'],
        ['route' => 'admin.lowongan.index',   'match' => 'admin.lowongan.*',       'icon' => 'work',           'label' => 'Lowongan Kerja'],
        ['route' => 'admin.lamaran.index',    'match' => 'admin.lamaran.*',        'icon' => 'assignment_ind', 'label' => 'Lamaran Masuk'],
        ['route' => 'admin.kerja-sama.index', 'match' => 'admin.kerja-sama.*',     'icon' => 'handshake',      'label' => 'Pengajuan Kerja Sama'],
        ['route' => 'admin.feedback.index',   'match' => 'admin.feedback.*',       'icon' => 'forum',          'label' => 'Feedback'],
        ['route' => 'admin.project.index',    'match' => 'admin.project.*',        'icon' => 'folder_copy',    'label' => 'Project'],
        ['route' => 'admin.log.index',        'match' => 'admin.log.*',            'icon' => 'history',        'label' => 'Log Aktivitas'],
        ['route' => 'admin.pengaturan.index', 'match' => 'admin.pengaturan.*',     'icon' => 'settings',       'label' => 'Pengaturan'],
    ];
@endphp

<aside id="app-sidebar" class="fixed left-0 top-0 h-full w-[260px] bg-primary z-50 flex flex-col justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)] transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    <div class="flex flex-col">
        {{-- Brand --}}
        <div class="h-16 px-space-lg flex items-center justify-between border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-space-md text-decoration-none">
                <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center font-headline-sm text-headline-sm text-on-primary-fixed font-bold tracking-wider shadow-sm">
                    MS
                </div>
                <div class="flex flex-col">
                    <span class="font-title-md text-title-md text-on-primary tracking-tight leading-snug">CV Maju Sukses</span>
                    <span class="font-label-sm text-label-sm text-secondary-fixed tracking-wide uppercase">Admin Portal</span>
                </div>
            </a>
            {{-- Mobile Close Button --}}
            <button type="button" onclick="toggleSidebar()" class="lg:hidden text-white/70 hover:text-white p-1 rounded-lg transition-colors" title="Tutup Menu">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        {{-- Menu --}}
        <div class="px-space-md pt-space-lg">
            <nav class="flex flex-col gap-space-xs">
                @foreach ($menu as $item)
                    @php $isActive = request()->routeIs($item['match']); @endphp
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       @if($isActive) aria-current="page" @endif
                       class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg transition-colors
                              font-title-md text-title-md
                              {{ $isActive
                                  ? 'bg-white/10 text-on-primary border-l-4 border-secondary-container pl-3'
                                  : 'text-outline-variant hover:text-on-primary hover:bg-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- User Footer --}}
    <div class="p-space-md border-t border-white/10">
        <div class="p-space-sm rounded-xl bg-white/5 flex items-center justify-between">
            <div class="flex items-center gap-space-sm min-w-0">
                <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-label-md text-label-md text-on-primary truncate font-semibold">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </span>
                    <span class="font-label-sm text-label-sm text-outline-variant truncate">
                        Admin Perusahaan
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="p-space-xs rounded-lg text-outline-variant hover:text-error hover:bg-white/10 transition-colors shrink-0 flex items-center justify-center" title="Logout">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>