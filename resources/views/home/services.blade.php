@php
    $company = \App\Models\CompanyProfile::current();
    $services = array_filter(array_map('trim', explode("\n", $company->services ?? '')));

    $serviceMeta = [
        ['icon' => 'local_shipping', 'desc' => 'Layanan pengiriman barang yang cepat, tepat, dan terjangkau untuk mitra usaha.'],
        ['icon' => 'storefront',     'desc' => 'Penyediaan aneka produk kebutuhan usaha dengan harga kompetitif dan stok terjamin.'],
        ['icon' => 'inventory_2',    'desc' => 'Pengelolaan persediaan modern dengan sistem pencatatan yang akurat dan efisien.'],
        ['icon' => 'support_agent',  'desc' => 'Konsultasi rantai pasok untuk membantu mitra mengoptimalkan operasional bisnis.'],
    ];
@endphp

<section class="services">
    <div class="container-public">

        <div class="services__header">
            <span class="section-tag">Layanan Kami</span>
            <h2 class="section-title">Solusi Lengkap untuk Kebutuhan Usaha Anda</h2>
            <p class="section-desc">
                Kami menyediakan berbagai layanan terpadu untuk mendukung pertumbuhan bisnis toko, UMKM, dan mitra usaha.
            </p>
        </div>

        <div class="services__grid">
            @foreach ($services as $i => $service)
                @php $meta = $serviceMeta[$i] ?? $serviceMeta[0]; @endphp
                <div class="service-card">
                    <div class="service-card__icon">
                        <span class="material-symbols-outlined">{{ $meta['icon'] }}</span>
                    </div>
                    <h3 class="service-card__title">{{ $service }}</h3>
                    <p class="service-card__desc">{{ $meta['desc'] }}</p>
                </div>
            @endforeach
        </div>

    </div>
</section>