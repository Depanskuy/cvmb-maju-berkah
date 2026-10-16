@extends('layouts.guest')

@section('title', 'Login — CV Maju Sukses')

@section('content')
<div class="auth-page">
    <div class="auth-shell">

        {{-- ============================================ --}}
        {{-- LEFT PANEL: Brand Narrative & Security      --}}
        {{-- ============================================ --}}
        <div class="auth-brand">

            {{-- Ambient background grid --}}
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

                {{-- Header branding --}}
                <div class="auth-brand__header">
                    <div class="auth-brand__identity">
                        <div class="auth-brand__logo">MS</div>
                        <div>
                            <div class="auth-brand__name">CV Maju Sukses</div>
                            <div class="auth-brand__subtitle">Sistem Terpadu</div>
                        </div>
                    </div>
                    <span class="auth-brand__pill">
                        <span class="auth-brand__pill-dot"></span>
                        Portal Akun
                    </span>
                </div>

                {{-- Center content --}}
                <div class="auth-brand__content">
                    <span class="auth-brand__tag">Akses Akun Terintegrasi</span>
                    <h1 class="auth-brand__title">Selamat Datang Kembali</h1>
                    <p class="auth-brand__description">
                        Masuk untuk mengakses layanan CV Maju Sukses, baik untuk pengelolaan operasional perusahaan maupun portal karir dan lamaran kerja Anda.
                    </p>

                    {{-- Security badges --}}
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

                {{-- Quote card --}}
                <div class="auth-brand__quote">
                    <span class="material-symbols-outlined auth-brand__quote-icon">format_quote</span>
                    <div>
                        <p class="auth-brand__quote-text">
                            "Satu portal terpadu untuk kemudahan pelamar memantau proses seleksi kerja dan tim perusahaan mengelola operasional secara efisien."
                        </p>
                        <p class="auth-brand__quote-author">
                            — CV Maju Sukses Portal
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ============================================ --}}
        {{-- RIGHT PANEL: Authentication Form            --}}
        {{-- ============================================ --}}
        <div class="auth-form-panel">
            <div class="auth-form-panel__inner">

                {{-- Gateway label --}}
                <div class="auth-form-panel__top">
                    <span class="auth-gateway">
                        <span class="auth-gateway__dot"></span>
                        Portal Masuk Terpadu
                    </span>
                </div>

                {{-- Main form --}}
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

                    {{-- Error global --}}
                    @if ($errors->any())
                        <div class="auth-alert auth-alert--error">
                            <span class="material-symbols-outlined" style="font-size:1.125rem;">error</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="auth-alert auth-alert--success">
                            <span class="material-symbols-outlined" style="font-size:1.125rem;">check_circle</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('login.store') }}" method="POST" class="auth-form">
                        @csrf

                        {{-- Email --}}
                        <div class="auth-field">
                            <label for="email" class="auth-field__label">Alamat Email</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">alternate_email</span>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="auth-field__input @error('email') is-invalid @enderror"
                                    placeholder="email@example.com"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    autofocus
                                    required>
                            </div>
                            @error('email')
                                <span class="auth-field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="auth-field">
                            <label for="password" class="auth-field__label">Kata Sandi</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">lock</span>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="auth-field__input @error('password') is-invalid @enderror"
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
                            @error('password')
                                <span class="auth-field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Options --}}
                        <div class="auth-options">
                            <label class="auth-options__remember">
                                <input type="checkbox" name="remember" value="1" class="auth-options__checkbox">
                                <span>Ingat saya selama 30 hari</span>
                            </label>
                            <a href="#" class="auth-options__forgot"
                               onclick="event.preventDefault(); alert('Silakan hubungi administrator CV Maju Sukses untuk bantuan pemulihan akun.');">
                                Lupa password?
                            </a>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="auth-submit">
                            <span>Masuk ke Akun</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>

                    <div style="margin-top: 1.25rem; text-align: center; font-size: 14px; color: var(--on-surface-variant);">
                        Belum memiliki akun pencari kerja?
                        <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: underline;">
                            Daftar di sini
                        </a>
                    </div>

                    {{-- Divider --}}
                    <div class="auth-divider">
                        <span class="auth-divider__label">atau</span>
                    </div>

                    {{-- Back link --}}
                    <div class="auth-back-link">
                        <a href="{{ route('home') }}">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Kembali ke Website Utama</span>
                        </a>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="auth-form-panel__footer">
                    <p>&copy; {{ date('Y') }} CV Maju Sukses. Seluruh hak cipta dilindungi.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
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
@endpush