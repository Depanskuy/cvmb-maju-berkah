@extends('layouts.public')

@section('title', '403 — Akses Ditolak — CV Maju Sukses')

@section('content')
<section class="career-main" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div class="container-public" style="max-width: 600px; text-align: center;">
        <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; border-radius: 1.25rem; background: rgba(239, 68, 68, 0.12); color: #ef4444; display: flex; align-items: center; justify-content: center;">
            <span class="material-symbols-outlined" style="font-size: 42px;">gpp_bad</span>
        </div>

        <span style="font-family: var(--font-display); font-size: 14px; font-weight: 700; color: #ef4444; letter-spacing: 0.1em; text-transform: uppercase;">
            Error 403 • Akses Terbatas
        </span>

        <h1 style="font-family: var(--font-display); font-size: 28px; font-weight: 700; color: var(--on-surface); margin: 0.5rem 0 1rem; letter-spacing: -0.01em;">
            Akses Ditolak
        </h1>

        <p style="font-size: 15px; line-height: 1.6; color: var(--on-surface-variant); margin: 0 0 2rem;">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses atau role yang sesuai untuk membuka halaman atau fitur ini. Silakan gunakan akun dengan hak akses yang berwenang.' }}
        </p>

        <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('home') }}" class="btn-page btn-page--dark" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; text-decoration: none;">
                <span class="material-symbols-outlined" style="font-size: 20px;">home</span>
                <span>Kembali ke Beranda</span>
            </a>
            @auth
                <a href="{{ route('careers.index') }}" class="btn-page btn-page--outline" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">work</span>
                    <span>Lihat Lowongan</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-page btn-page--outline" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                    <span>Login Akun</span>
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection
