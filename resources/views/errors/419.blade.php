@extends('layouts.public')

@section('title', '419 — Sesi Kedaluwarsa — CV Maju Sukses')

@section('content')
<section class="career-main" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div class="container-public" style="max-width: 600px; text-align: center;">
        <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; border-radius: 1.25rem; background: rgba(254, 174, 44, 0.15); color: #d97706; display: flex; align-items: center; justify-content: center;">
            <span class="material-symbols-outlined" style="font-size: 42px;">history_toggle_off</span>
        </div>

        <span style="font-family: var(--font-display); font-size: 14px; font-weight: 700; color: #d97706; letter-spacing: 0.1em; text-transform: uppercase;">
            Error 419 • Sesi Berakhir
        </span>

        <h1 style="font-family: var(--font-display); font-size: 28px; font-weight: 700; color: var(--on-surface); margin: 0.5rem 0 1rem; letter-spacing: -0.01em;">
            Sesi Anda Telah Kedaluwarsa
        </h1>

        <p style="font-size: 15px; line-height: 1.6; color: var(--on-surface-variant); margin: 0 0 2rem;">
            Demi keamanan data dan transaksi, sesi formulir Anda telah berakhir karena tidak ada aktivitas dalam waktu tertentu. Silakan segarkan halaman atau login kembali.
        </p>

        <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="javascript:location.reload()" class="btn-page btn-page--dark" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; text-decoration: none;">
                <span class="material-symbols-outlined" style="font-size: 20px;">refresh</span>
                <span>Muat Ulang Halaman</span>
            </a>
            <a href="{{ route('login') }}" class="btn-page btn-page--outline" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; text-decoration: none;">
                <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                <span>Login Kembali</span>
            </a>
        </div>
    </div>
</section>
@endsection
