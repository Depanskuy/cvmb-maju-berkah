<?php $company = \App\Models\Company::current(); ?>

<footer class="public-footer">
    <div class="container-public public-footer__inner">

        <div class="public-footer__grid">

            
            <div class="public-footer__brand">
                <div class="public-footer__logo">
                    <?php echo $__env->make('partials.brand-mark', ['class' => 'public-footer__logo-icon'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span class="public-footer__name"><?php echo e($company->name); ?></span>
                </div>
                <p class="public-footer__desc"><?php echo e($company->tagline); ?></p>
                <div class="public-footer__badge">
                    <span class="material-symbols-outlined">verified</span>
                    <span>Mitra Bisnis Resmi Terdaftar</span>
                </div>
            </div>

            
            <div class="public-footer__col">
                <h4 class="public-footer__heading">Perusahaan</h4>
                <ul class="public-footer__list">
                    <li><a href="<?php echo e(route('about')); ?>">Tentang Kami</a></li>
                    <li><a href="<?php echo e(route('services')); ?>">Layanan</a></li>
                    <li><a href="<?php echo e(route('careers.index')); ?>">Karir &amp; Rekrutmen</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>">Kontak</a></li>
                </ul>
            </div>

            
            <div class="public-footer__col">
                <h4 class="public-footer__heading">Layanan</h4>
                <ul class="public-footer__list">
                    <li><a href="<?php echo e(route('services')); ?>">Distribusi Barang</a></li>
                    <li><a href="<?php echo e(route('services')); ?>">Penyediaan Kebutuhan Usaha</a></li>
                    <li><a href="<?php echo e(route('services')); ?>">Manajemen Persediaan</a></li>
                    <li><a href="<?php echo e(route('services')); ?>">Konsultasi Rantai Pasok</a></li>
                </ul>
            </div>

            
            <div class="public-footer__col">
                <h4 class="public-footer__heading">Kontak</h4>
                <div class="public-footer__contact">
                    <div class="public-footer__contact-row">
                        <span class="material-symbols-outlined">location_on</span>
                        <span><?php echo e($company->address); ?></span>
                    </div>
                    <div class="public-footer__contact-row">
                        <span class="material-symbols-outlined">mail</span>
                        <span><?php echo e($company->contact_email); ?></span>
                    </div>
                    <div class="public-footer__contact-row">
                        <span class="material-symbols-outlined">call</span>
                        <span><?php echo e($company->contact_phone); ?></span>
                    </div>
                </div>
            </div>

        </div>

        <div class="public-footer__bottom">
            <p>© <?php echo e(date('Y')); ?> <?php echo e($company->name); ?>. Seluruh hak cipta dilindungi undang-undang.</p>
            <div class="public-footer__bottom-links">
                <a href="<?php echo e(route('contact')); ?>">Kebijakan Privasi</a>
                <a href="<?php echo e(route('contact')); ?>">Syarat &amp; Ketentuan</a>
            </div>
        </div>

    </div>
</footer><?php /**PATH C:\laragon\www\cvmb-pembagian-kerja\1-auth-dan-integrasi\resources\views/partials/footer.blade.php ENDPATH**/ ?>