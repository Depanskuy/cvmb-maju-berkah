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
        </nav>

        <div class="public-navbar__actions">
            <a href="{{ route('careers.index') }}" class="public-navbar__cta">
                Lihat Lowongan
            </a>
            <a href="{{ route('login') }}" class="public-navbar__user" title="Login Admin">
                <span class="material-symbols-outlined">person</span>
            </a>
        </div>

    </div>
</header>