@php $company = \App\Models\Company::current(); @endphp

<section class="about">
    <div class="container-public">
        <div class="about__grid">

            {{-- Visual --}}
            <div class="about__visual">
                <div class="about__visual-main">
                    <img class="about__visual-img"
                         src="{{ asset('images/team-meeting.jpg') }}"
                         alt="Tim CV Maju Berkah berdiskusi di kantor"/>
                </div>
                <div class="about__badge">
                    <span class="material-symbols-outlined">verified</span>
                    <div>
                        <div class="about__badge-title">Terpercaya</div>
                        <div class="about__badge-sub">Sejak 2010</div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="about__content">
                <span class="section-tag">Tentang Kami</span>
                <h2 class="about__title">Bertumbuh Bersama Mitra Usaha Indonesia</h2>
                <p class="about__desc">{{ $company->description }}</p>

                <div class="about__cards">

                    <div class="about-card">
                        <div class="about-card__icon about-card__icon--primary">
                            <span class="material-symbols-outlined">visibility</span>
                        </div>
                        <h3 class="about-card__title">Visi</h3>
                        <p class="about-card__text">{{ $company->vision }}</p>
                    </div>

                    <div class="about-card">
                        <div class="about-card__icon about-card__icon--secondary">
                            <span class="material-symbols-outlined">flag</span>
                        </div>
                        <h3 class="about-card__title">Misi</h3>
                        <p class="about-card__text">{{ $company->mission }}</p>
                    </div>

                </div>

                <div class="about__link-wrap">
                    <a href="{{ route('about') }}" class="section-link">
                        <span>Selengkapnya tentang kami</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>