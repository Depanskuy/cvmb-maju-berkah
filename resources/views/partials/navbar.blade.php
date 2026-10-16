<header class="public-navbar">
    <div class="container-public public-navbar__inner">

        <a href="{{ route('home') }}" class="public-navbar__brand">
            <div class="public-navbar__logo"><span>MS</span></div>
            <span class="public-navbar__name">CV Maju Sukses</span>
        </a>

        <nav class="public-navbar__menu">
            @php
                $navItems = [
                    ['route' => 'home',          'label' => 'Beranda'],
                    ['route' => 'about',         'label' => 'Tentang'],
                    ['route' => 'services',      'label' => 'Layanan'],
                    ['route' => 'careers.index', 'label' => 'Karir'],
                    ['route' => 'contact',       'label' => 'Kontak'],
                ];
            @endphp
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="public-navbar__link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            @auth
                @if (auth()->user()->isSeeker())
                    <a href="{{ route('seeker.dashboard') }}"
                       class="public-navbar__link {{ request()->routeIs('seeker.dashboard') ? 'is-active' : '' }}"
                       style="font-weight:600; color:var(--primary);">
                        Dashboard Saya
                    </a>
                    <a href="{{ route('seeker.applications') }}"
                       class="public-navbar__link {{ request()->routeIs('seeker.applications*') ? 'is-active' : '' }}">
                        Lamaran Saya
                    </a>
                @elseif (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="public-navbar__link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}"
                       style="font-weight:600; color:var(--primary);">
                        Panel Admin
                    </a>
                @endif
            @endauth
        </nav>

        <div class="public-navbar__actions">
            @guest
                <a href="{{ route('login') }}" class="public-navbar__link" style="font-weight:600; color:var(--primary);">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="public-navbar__cta" style="background:var(--primary); color:#fff; box-shadow:none;">
                    Daftar
                </a>
            @else
                @if (auth()->user()->isSeeker())
                    <a href="{{ route('seeker.dashboard') }}"
                       class="public-navbar__cta"
                       style="padding:0.4rem 1rem; font-size:13px; background:var(--primary);">
                        Dashboard
                    </a>
                    <a href="{{ route('seeker.profile.show') }}"
                       class="public-navbar__link"
                       style="display:inline-flex; align-items:center; gap:0.35rem; font-weight:600; font-size:14px;"
                       title="Lihat &amp; Kelola Profil Pencari Kerja">
                        <span class="material-symbols-outlined" style="font-size:20px; color:var(--primary);">account_circle</span>
                        <span class="hidden md:inline">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 14) }}</span>
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}"
                       class="public-navbar__cta"
                       style="padding:0.4rem 1rem; font-size:13px;">
                        Dashboard Admin
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                    @csrf
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:0.25rem; font-size:13px; font-weight:500; color:#ba1a1a; padding:0.4rem 0.6rem; border-radius:6px; background:rgba(186,26,26,0.06); transition:background 0.2s;"
                            title="Keluar dari akun">
                        <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            @endguest
        </div>

    </div>
</header>