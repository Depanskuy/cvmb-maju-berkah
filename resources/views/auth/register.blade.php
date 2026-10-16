@extends('layouts.guest')

@section('title', 'Daftar Pencari Kerja — CV Maju Sukses')

@section('content')
<div class="auth-page">
    <div class="auth-shell">

        {{-- LEFT PANEL --}}
        <div class="auth-brand">
            <svg class="auth-brand__grid" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <pattern id="corporate-grid-reg" width="48" height="48" patternUnits="userSpaceOnUse">
                        <path d="M 48 0 L 0 0 0 48" fill="none" stroke="currentColor" stroke-width="0.75"/>
                        <circle cx="0" cy="0" r="1.5" fill="currentColor" opacity="0.6"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#corporate-grid-reg)"/>
            </svg>

            <div class="auth-brand__inner">
                <div class="auth-brand__header">
                    <div class="auth-brand__identity">
                        <div class="auth-brand__logo">MS</div>
                        <div>
                            <div class="auth-brand__name">CV Maju Sukses</div>
                            <div class="auth-brand__subtitle">Karier &amp; Rekrutmen</div>
                        </div>
                    </div>
                    <span class="auth-brand__pill">
                        <span class="auth-brand__pill-dot"></span>
                        Portal Pencari Kerja
                    </span>
                </div>

                <div class="auth-brand__content">
                    <span class="auth-brand__tag">Langkah Awal Karier</span>
                    <h1 class="auth-brand__title">Daftar &amp; Temukan Karier Impian</h1>
                    <p class="auth-brand__description">
                        Buat akun pencari kerja untuk melamar berbagai posisi strategis di CV Maju Sukses dan pantau status berkas lamaran Anda secara langsung.
                    </p>

                    <div class="auth-brand__badges">
                        <div class="auth-badge">
                            <span class="material-symbols-outlined auth-badge__icon">badge</span>
                            <span class="auth-badge__title">Profil Terpadu</span>
                            <span class="auth-badge__desc">Kelola resume &amp; CV</span>
                        </div>
                        <div class="auth-badge">
                            <span class="material-symbols-outlined auth-badge__icon">track_changes</span>
                            <span class="auth-badge__title">Status Realtime</span>
                            <span class="auth-badge__desc">Pantau proses seleksi</span>
                        </div>
                        <div class="auth-badge">
                            <span class="material-symbols-outlined auth-badge__icon">lock</span>
                            <span class="auth-badge__title">Data Aman</span>
                            <span class="auth-badge__desc">Privasi terjamin</span>
                        </div>
                    </div>
                </div>

                <div class="auth-brand__quote">
                    <span class="material-symbols-outlined auth-brand__quote-icon">format_quote</span>
                    <div>
                        <p class="auth-brand__quote-text">
                            "Bergabunglah bersama talenta profesional kami untuk mendorong kemajuan ekosistem bisnis di Indonesia."
                        </p>
                        <p class="auth-brand__quote-author">
                            — Tim People &amp; Talent CV Maju Sukses
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: Register Form --}}
        <div class="auth-form-panel">
            <div class="auth-form-panel__inner">

                <div class="auth-form-panel__top">
                    <span class="auth-gateway">
                        <span class="auth-gateway__dot"></span>
                        Pendaftaran Pencari Kerja
                    </span>
                </div>

                <div class="auth-form-panel__main">
                    <div class="auth-form-header">
                        <div class="auth-form-header__icon">
                            <span class="material-symbols-outlined">person_add</span>
                        </div>
                        <h2 class="auth-form-header__title">Buat Akun Baru</h2>
                        <p class="auth-form-header__subtitle">
                            Lengkapi data diri Anda untuk mulai melamar pekerjaan
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="auth-alert auth-alert--error">
                            <span class="material-symbols-outlined" style="font-size:1.125rem;">error</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form action="{{ route('register.store') }}" method="POST" class="auth-form">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="auth-field">
                            <label for="name" class="auth-field__label">Nama Lengkap</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">person</span>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="auth-field__input @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Budi Santoso"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus>
                            </div>
                            @error('name')
                                <span class="auth-field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="auth-field">
                            <label for="email" class="auth-field__label">Alamat Email Aktif</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">alternate_email</span>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="auth-field__input @error('email') is-invalid @enderror"
                                    placeholder="budi@example.com"
                                    value="{{ old('email') }}"
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
                                    placeholder="Minimal 6 karakter"
                                    required>
                            </div>
                            @error('password')
                                <span class="auth-field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="auth-field">
                            <label for="password_confirmation" class="auth-field__label">Konfirmasi Kata Sandi</label>
                            <div class="auth-field__wrap">
                                <span class="material-symbols-outlined auth-field__icon">lock_reset</span>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="auth-field__input"
                                    placeholder="Ulangi kata sandi"
                                    required>
                            </div>
                        </div>

                        <button type="submit" class="auth-submit">
                            <span>Daftar Akun Sekarang</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>

                    <div style="margin-top: 1.25rem; text-align: center; font-size: 14px; color: var(--on-surface-variant);">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: underline;">
                            Masuk di sini
                        </a>
                    </div>

                    <div class="auth-divider">
                        <span class="auth-divider__label">atau</span>
                    </div>

                    <div class="auth-back-link">
                        <a href="{{ route('home') }}">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Kembali ke Website Utama</span>
                        </a>
                    </div>
                </div>

                <div class="auth-form-panel__footer">
                    <p>&copy; {{ date('Y') }} CV Maju Sukses. Seluruh hak cipta dilindungi.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
