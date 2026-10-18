<?php $__env->startSection('title', 'Login — CV Maju Berkah'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-page">
    <div class="auth-shell">

        
        
        
        <div class="auth-brand">

            
            <svg class="auth-brand__grid" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <pattern id="corporate-grid" width="48" height="48" patternUnits="userSpaceOnUse">
                        <path d="M 48 0 L 0 0 0 48" fill="none" stroke="currentColor" stroke-width="0.75"/>
                        <circle cx="0" cy="0" r="1.5" fill="currentColor" opacity="0.6"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#corporate-grid)"/>
            </svg>

            <div class="auth-brand__inner">

                
                <div class="auth-brand__header">
                    <div class="auth-brand__identity">
                        <?php echo $__env->make('partials.brand-mark', ['class' => 'auth-brand__logo'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <div>
                            <div class="auth-brand__name">CV Maju Berkah</div>
                            <div class="auth-brand__subtitle">Sistem Terpadu</div>
                        </div>
                    </div>
                    <span class="auth-brand__pill">
                        <span class="auth-brand__pill-dot"></span>
                        Portal Akun
                    </span>
                </div>

                
                <div class="auth-brand__content">
                    <span class="auth-brand__tag">Akses Akun Terintegrasi</span>
                    <h1 class="auth-brand__title">Selamat Datang Kembali</h1>
                    <p class="auth-brand__description">
                        Masuk untuk mengakses layanan CV Maju Berkah, baik untuk pengelolaan operasional perusahaan maupun portal karir dan lamaran kerja Anda.
                    </p>

                    
                    <div class="auth-brand__badges">
                        <div class="auth-badge">
                            <span class="material-symbols-outlined auth-badge__icon">verified_user</span>
                            <span class="auth-badge__title">2FA Protected</span>
                            <span class="auth-badge__desc">Multi-factor ready</span>
                        </div>
                        <div class="auth-badge">
                            <span class="material-symbols-outlined auth-badge__icon">lock</span>
                            <span class="auth-badge__title">SSL 256-Bit</span>
                            <span class="auth-badge__desc">Data terenkripsi</span>
                        </div>
                        <div class="auth-badge">
                            <span class="material-symbols-outlined auth-badge__icon">workspace_premium</span>
                            <span class="auth-badge__title">ISO 27001</span>
                            <span class="auth-badge__desc">Standar keamanan</span>
                        </div>
                    </div>
                </div>

                
                <div class="auth-brand__quote">
                    <span class="material-symbols-outlined auth-brand__quote-icon">format_quote</span>
                    <div>
                        <p class="auth-brand__quote-text">
                            "Satu portal terpadu untuk kemudahan pelamar memantau proses seleksi kerja dan tim perusahaan mengelola operasional secara efisien."
                        </p>
                        <p class="auth-brand__quote-author">
                            — CV Maju Berkah Portal
                        </p>
                    </div>
                </div>

            </div>
        </div>

        
        
        
        <div class="auth-form-panel">
            <div class="auth-form-panel__inner">

                
                <div class="auth-form-panel__top">
                    <span class="auth-gateway">
                        <span class="auth-gateway__dot"></span>
                        Portal Masuk Terpadu
                    </span>
                </div>

                
                <div class="auth-form-panel__main">

                    <div class="auth-form-header">
                        <div class="auth-form-header__icon">
                            <span class="material-symbols-outlined">shield_person</span>
                        </div>
                        <h2 class="auth-form-header__title">Masuk ke Akun Anda</h2>
                        <p class="auth-form-header__subtitle">
                            Silakan masuk dengan akun Admin Perusahaan atau Pencari Kerja
                        </p>
                    </div>

                    
                    <?php if($errors->any()): ?>
                        <div class="auth-alert auth-alert--error">
                            <span class="material-symbols-outlined" style="font-size:1.125rem;">error</span>
                            <span><?php echo e($errors->first()); ?></span>
                        </div>
                    <?php endif; ?>

                    
                    <?php if(session('success')): ?>
                        <div class="auth-alert auth-alert--success">
                            <span class="material-symbols-outlined" style="font-size:1.125rem;">check_circle</span>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('login.store')); ?>" method="POST" class="auth-form">
                        <?php echo csrf_field(); ?>

                        
                        <div class="auth-field">
                            <label for="email" class="auth-field__label">Alamat Email</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">alternate_email</span>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="auth-field__input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="email@example.com"
                                    value="<?php echo e(old('email')); ?>"
                                    autocomplete="email"
                                    autofocus
                                    required>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="auth-field__error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="auth-field">
                            <label for="password" class="auth-field__label">Kata Sandi</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">lock</span>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="auth-field__input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="••••••••••••"
                                    autocomplete="current-password"
                                    required>
                                <button type="button"
                                        id="toggle-password-btn"
                                        class="auth-field__toggle"
                                        aria-label="Tampilkan kata sandi"
                                        onclick="togglePasswordVisibility()">
                                    <span class="material-symbols-outlined" id="eye-icon">visibility</span>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="auth-field__error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="auth-options">
                            <label class="auth-options__remember">
                                <input type="checkbox" name="remember" value="1" class="auth-options__checkbox">
                                <span>Ingat saya selama 30 hari</span>
                            </label>
                            <a href="#" class="auth-options__forgot"
                               onclick="event.preventDefault(); alert('Silakan hubungi administrator CV Maju Berkah untuk bantuan pemulihan akun.');">
                                Lupa password?
                            </a>
                        </div>

                        
                        <button type="submit" class="auth-submit">
                            <span>Masuk ke Akun</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>

                    <div style="margin-top: 1.25rem; text-align: center; font-size: 14px; color: var(--on-surface-variant);">
                        Belum memiliki akun pencari kerja?
                        <a href="<?php echo e(route('register')); ?>" style="color: var(--primary); font-weight: 600; text-decoration: underline;">
                            Daftar di sini
                        </a>
                    </div>

                    
                    <div class="auth-divider">
                        <span class="auth-divider__label">atau</span>
                    </div>

                    
                    <div class="auth-back-link">
                        <a href="<?php echo e(route('home')); ?>">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Kembali ke Website Utama</span>
                        </a>
                    </div>
                </div>

                
                <div class="auth-form-panel__footer">
                    <p>&copy; <?php echo e(date('Y')); ?> CV Maju Berkah. Seluruh hak cipta dilindungi.</p>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        if (!passInput || !eyeIcon) return;

        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.textContent = 'visibility_off';
        } else {
            passInput.type = 'password';
            eyeIcon.textContent = 'visibility';
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cvmb-pembagian-kerja\1-auth-dan-integrasi\resources\views/auth/login.blade.php ENDPATH**/ ?>