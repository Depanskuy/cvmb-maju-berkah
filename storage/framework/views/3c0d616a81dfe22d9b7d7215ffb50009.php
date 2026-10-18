<header class="public-navbar">
    <div class="container-public public-navbar__inner">

        <a href="<?php echo e(route('home')); ?>" class="public-navbar__brand">
            <?php echo $__env->make('partials.brand-mark', ['class' => 'public-navbar__logo'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <span class="public-navbar__name">CV Maju Berkah</span>
        </a>

        <nav class="public-navbar__menu">
            <?php
                $navItems = [
                    ['route' => 'home',          'label' => 'Beranda'],
                    ['route' => 'about',         'label' => 'Tentang'],
                    ['route' => 'services',      'label' => 'Layanan'],
                    ['route' => 'careers.index', 'label' => 'Karir'],
                    ['route' => 'contact',       'label' => 'Kontak'],
                ];
            ?>
            <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($item['route'])); ?>"
                   class="public-navbar__link <?php echo e(request()->routeIs($item['route']) ? 'is-active' : ''); ?>">
                    <?php echo e($item['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isSeeker()): ?>
                    <a href="<?php echo e(route('job-seeker.dashboard')); ?>"
                       class="public-navbar__link <?php echo e(request()->routeIs('job-seeker.dashboard') ? 'is-active' : ''); ?>"
                       style="font-weight:600; color:var(--primary);">
                        Dashboard Saya
                    </a>
                    <a href="<?php echo e(route('job-seeker.applications.index')); ?>"
                       class="public-navbar__link <?php echo e(request()->routeIs('job-seeker.applications.index*') ? 'is-active' : ''); ?>">
                        Lamaran Saya
                    </a>
                <?php elseif(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('company.dashboard')); ?>"
                       class="public-navbar__link <?php echo e(request()->routeIs('admin.*') ? 'is-active' : ''); ?>"
                       style="font-weight:600; color:var(--primary);">
                        Panel Admin
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <div class="public-navbar__actions">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="public-navbar__link" style="font-weight:600; color:var(--primary);">
                    Masuk
                </a>
                <a href="<?php echo e(route('register')); ?>" class="public-navbar__cta" style="background:var(--primary); color:#fff; box-shadow:none;">
                    Daftar
                </a>
            <?php else: ?>
                <?php if(auth()->user()->isSeeker()): ?>
                    <a href="<?php echo e(route('job-seeker.dashboard')); ?>"
                       class="public-navbar__cta"
                       style="padding:0.4rem 1rem; font-size:13px; background:var(--primary);">
                        Dashboard
                    </a>
                    <a href="<?php echo e(route('job-seeker.profile.show')); ?>"
                       class="public-navbar__link"
                       style="display:inline-flex; align-items:center; gap:0.35rem; font-weight:600; font-size:14px;"
                       title="Lihat &amp; Kelola Profil Pencari Kerja">
                        <span class="material-symbols-outlined" style="font-size:20px; color:var(--primary);">account_circle</span>
                        <span class="hidden md:inline"><?php echo e(\Illuminate\Support\Str::limit(auth()->user()->name, 14)); ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('company.dashboard')); ?>"
                       class="public-navbar__cta"
                       style="padding:0.4rem 1rem; font-size:13px;">
                        Dashboard Admin
                    </a>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline; margin:0;">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:0.25rem; font-size:13px; font-weight:500; color:#ba1a1a; padding:0.4rem 0.6rem; border-radius:6px; background:rgba(186,26,26,0.06); transition:background 0.2s;"
                            title="Keluar dari akun">
                        <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            <?php endif; ?>
        </div>

    </div>
</header><?php /**PATH C:\laragon\www\cvmb-pembagian-kerja\1-auth-dan-integrasi\resources\views/partials/navbar.blade.php ENDPATH**/ ?>