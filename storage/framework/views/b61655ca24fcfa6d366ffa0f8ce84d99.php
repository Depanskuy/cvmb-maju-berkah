
<?php
    $brandLogoPath = \App\Models\Company::current()?->logo_path;
    $brandLogoUrl  = null;

    if ($brandLogoPath) {
        $brandLogoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($brandLogoPath);
    } else {
        foreach (['png', 'svg', 'jpg', 'jpeg', 'webp'] as $brandExt) {
            if (file_exists(public_path("images/logo.{$brandExt}"))) {
                $brandLogoUrl = asset("images/logo.{$brandExt}");
                break;
            }
        }
    }

    $brandWrapper = $class ?? 'brand-mark';
?>

<?php if($brandLogoUrl): ?>
    
    <div class="<?php echo e($brandWrapper); ?> brand-mark--image"
         style="background:#FFFFFF; padding:0.15rem; overflow:hidden; box-sizing:border-box; box-shadow:none;">
        <img src="<?php echo e($brandLogoUrl); ?>"
             alt="Logo CV Maju Berkah"
             style="width:100%; height:100%; object-fit:contain; display:block;">
    </div>
<?php else: ?>
    <div class="<?php echo e($brandWrapper); ?>"><span>MB</span></div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\cvmb-pembagian-kerja\1-auth-dan-integrasi\resources\views/partials/brand-mark.blade.php ENDPATH**/ ?>