{{--
    Logo perusahaan (hasil unggahan di Pengaturan /company/profile) dengan
    fallback monogram "MB" bila belum ada logo.

    Pemakaian:
        @include('partials.brand-mark', ['class' => 'public-navbar__logo'])
--}}
@php
    $brandLogoPath = \App\Models\Company::current()?->logo_path;
    $brandWrapper  = $class ?? 'brand-mark';
@endphp

@if ($brandLogoPath)
    <div class="{{ $brandWrapper }} brand-mark--image"
         style="background:#FFFFFF; padding:0.25rem; overflow:hidden; box-sizing:border-box;">
        <img src="{{ Storage::disk('public')->url($brandLogoPath) }}"
             alt="Logo CV Maju Berkah"
             style="width:100%; height:100%; object-fit:contain; display:block;">
    </div>
@else
    <div class="{{ $brandWrapper }}"><span>MB</span></div>
@endif
