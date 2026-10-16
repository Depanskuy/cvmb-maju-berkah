@php $company = \App\Models\CompanyProfile::current(); @endphp

<section class="hero">
    <div class="hero__pattern"></div>
    <div class="hero__glow-1"></div>
    <div class="hero__glow-2"></div>

    <div class="container-public hero__inner">
        <div class="hero__grid">

            {{-- Left: content --}}
            <div>
                <div class="hero__tag">
                    <span class="hero__tag-dot"></span>
                    <span>Perusahaan Distribusi &amp; Penyediaan Kebutuhan Usaha</span>
                </div>

                <h1 class="hero__title">{{ $company->tagline }}</h1>

                <p class="hero__desc">
                    {{ \Illuminate\Support\Str::limit($company->description, 220) }}
                </p>

                <div class="hero__cta">
                    <a href="{{ route('careers.index') }}" class="btn btn--primary">
                        <span>Lihat Lowongan</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <a href="{{ route('about') }}" class="btn btn--outline">
                        Tentang Kami
                    </a>
                </div>
            </div>

            {{-- Right: trust cards --}}
            <div class="hero__visual">

                <div class="hero__card">
                    <div class="hero__card-head">
                        <div class="hero__card-icon">
                            <span class="material-symbols-outlined">local_shipping</span>
                        </div>
                        <span class="hero__card-title">Distribusi Terpercaya</span>
                    </div>
                    <p class="hero__card-text">
                        Jaringan distribusi luas dengan standar operasional modern untuk toko, UMKM, dan mitra bisnis.
                    </p>
                </div>

                <div class="hero__card">
                    <div class="hero__card-head">
                        <div class="hero__card-icon">
                            <span class="material-symbols-outlined">workspace_premium</span>
                        </div>
                        <span class="hero__card-title">Standar Kualitas</span>
                    </div>
                    <p class="hero__card-text">
                        Komitmen penuh terhadap kualitas layanan dan kepuasan mitra usaha di seluruh Indonesia.
                    </p>
                </div>

                <div class="hero__card">
                    <div class="hero__card-head">
                        <div class="hero__card-icon">
                            <span class="material-symbols-outlined">trending_up</span>
                        </div>
                        <span class="hero__card-title">Pertumbuhan Berkelanjutan</span>
                    </div>
                    <p class="hero__card-text">
                        Bertumbuh bersama mitra melalui inovasi layanan dan pengelolaan persediaan yang efisien.
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>