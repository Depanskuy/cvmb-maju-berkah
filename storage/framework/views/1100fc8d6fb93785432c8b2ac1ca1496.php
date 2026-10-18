<?php
    $company = \App\Models\Company::current();
    $services = array_filter(array_map('trim', explode("\n", $company->services ?? '')));

    $serviceMeta = [
        ['icon' => 'local_shipping', 'desc' => 'Layanan pengiriman barang yang cepat, tepat, dan terjangkau untuk mitra usaha.'],
        ['icon' => 'storefront',     'desc' => 'Penyediaan aneka produk kebutuhan usaha dengan harga kompetitif dan stok terjamin.'],
        ['icon' => 'inventory_2',    'desc' => 'Pengelolaan persediaan modern dengan sistem pencatatan yang akurat dan efisien.'],
        ['icon' => 'support_agent',  'desc' => 'Konsultasi rantai pasok untuk membantu mitra mengoptimalkan operasional bisnis.'],
    ];
?>

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
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $meta = $serviceMeta[$i] ?? $serviceMeta[0]; ?>
                <div class="service-card">
                    <div class="service-card__icon">
                        <span class="material-symbols-outlined"><?php echo e($meta['icon']); ?></span>
                    </div>
                    <h3 class="service-card__title"><?php echo e($service); ?></h3>
                    <p class="service-card__desc"><?php echo e($meta['desc']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section><?php /**PATH C:\laragon\www\cvmb-pembagian-kerja\1-auth-dan-integrasi\resources\views/home/services.blade.php ENDPATH**/ ?>