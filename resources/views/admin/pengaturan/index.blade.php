@extends('layouts.admin')

@section('title', 'Pengaturan Sistem — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-4xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Pengaturan Sistem &amp; Profil Perusahaan
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Kelola identitas resmi perusahaan, kontak korespondensi, dan informasi legalitas operasional.
            </p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="p-6 sm:p-8 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pb-4 border-b border-outline-variant/30">
                <h2 class="text-base font-bold font-display text-on-surface">Identitas Badan Usaha</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Data berikut tampil pada kop resmi, portal pelamar, dan korespondensi rekrutmen.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nama Perusahaan <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $company->name ?? 'CV Maju Sukses') }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Alamat Email Resmi <span class="text-rose-600">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $company->email ?? 'hrd@majusukses.co.id') }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nomor Telepon Kantor
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone ?? '(021) 555-0199') }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="portal_status" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Status Sistem &amp; Server
                    </label>
                    <input type="text" id="portal_status" value="Online — Laravel 12 / PHP 8.4 Production Ready" readonly
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/30 bg-surface-container-low text-on-surface-variant text-sm cursor-not-allowed">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Alamat Kantor Pusat
                </label>
                <textarea id="address" name="address" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('address', $company->address ?? 'Kawasan Bisnis Sudirman, Jakarta Selatan') }}</textarea>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Profil Singkat &amp; Visi Perusahaan
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('description', $company->description ?? 'CV Maju Sukses adalah badan usaha terkemuka yang bergerak di bidang konstruksi sipil, mechanical electrical plumbing (MEP), dan pengadaan barang berkualitas tinggi.') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
