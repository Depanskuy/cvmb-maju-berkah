{{--
    Logo perusahaan dengan tiga tingkat prioritas:
      1. Logo hasil unggahan di Pengaturan  (storage/app/public/logos/...)
      2. File statis  public/images/logo.png|svg|jpg|jpeg|webp
      3. Fallback monogram "MB"

    Pemakaian:
        @include('partials.brand-mark', ['class' => 'public-navbar__logo'])
--}}
@php
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
@endphp

@if ($brandLogoUrl)
    <div class="{{ $brandWrapper }} brand-mark--image"
         style="background:#FFFFFF; padding:0.25rem; overflow:hidden; box-sizing:border-box;">
        <img src="{{ $brandLogoUrl }}"
             alt="Logo CV Maju Berkah"
             style="width:100%; height:100%; object-fit:contain; display:block;">
    </div>
@else
    <div class="{{ $brandWrapper }}"><span>MB</span></div>
@endif
