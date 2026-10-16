@php $company = \App\Models\CompanyProfile::current(); @endphp

<footer class="public-footer">
    <div class="container-public public-footer__inner">

        <div class="public-footer__grid">

            {{-- Brand --}}
            <div class="public-footer__brand">
                <div class="public-footer__logo">
                    <div class="public-footer__logo-icon"><span>MS</span></div>
                    <span class="public-footer__name">{{ $company->name }}</span>
                </div>
                <p class="public-footer__desc">{{ $company->tagline }}</p>
                <div class="public-footer__badge">
                    <span class="material-symbols-outlined">verified</span>
                    <span>Mitra Bisnis Resmi Terdaftar</span>
                </div>
            </div>

            {{-- Perusahaan --}}
            <div class="public-footer__col">
                <h4 class="public-footer__heading">Perusahaan</h4>
                <ul class="public-footer__list">
                    <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('services') }}">Layanan</a></li>
                    <li><a href="{{ route('careers.index') }}">Karir &amp; Rekrutmen</a></li>
                    <li><a href="{{ route('contact') }}">Kontak</a></li>
                </ul>
            </div>

            {{-- Layanan --}}
            <div class="public-footer__col">
                <h4 class="public-footer__heading">Layanan</h4>
                <ul class="public-footer__list">
                    <li><a href="{{ route('services') }}">Distribusi Barang</a></li>
                    <li><a href="{{ route('services') }}">Penyediaan Kebutuhan Usaha</a></li>
                    <li><a href="{{ route('services') }}">Manajemen Persediaan</a></li>
                    <li><a href="{{ route('services') }}">Konsultasi Rantai Pasok</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="public-footer__col">
                <h4 class="public-footer__heading">Kontak</h4>
                <div class="public-footer__contact">
                    <div class="public-footer__contact-row">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>{{ $company->address }}</span>
                    </div>
                    <div class="public-footer__contact-row">
                        <span class="material-symbols-outlined">mail</span>
                        <span>{{ $company->contact_email }}</span>
                    </div>
                    <div class="public-footer__contact-row">
                        <span class="material-symbols-outlined">call</span>
                        <span>{{ $company->contact_phone }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="public-footer__bottom">
            <p>© {{ date('Y') }} {{ $company->name }}. Seluruh hak cipta dilindungi undang-undang.</p>
            <div class="public-footer__bottom-links">
                <a href="{{ route('contact') }}">Kebijakan Privasi</a>
                <a href="{{ route('contact') }}">Syarat &amp; Ketentuan</a>
            </div>
        </div>

    </div>
</footer>