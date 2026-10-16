@extends('layouts.seeker')

@section('title', 'Edit Profil — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-4xl mx-auto">

    {{-- Breadcrumb & Back navigation --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('seeker.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('seeker.profile.show') }}" class="hover:text-primary transition-colors">Profil Saya</a>
                <span>/</span>
                <span class="text-on-surface font-semibold">Edit Profil</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Perbarui Profil &amp; CV
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Pastikan data kontak dan berkas kualifikasi Anda selalu akurat agar mudah dihubungi oleh tim rekrutmen.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('seeker.profile.show') }}" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm flex items-center gap-2 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- Error messages --}}
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-2">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <span>Terdapat Kesalahan Pengisian:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm pl-2">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="p-6 sm:p-8 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
        <form method="POST" action="{{ route('seeker.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pb-4 border-b border-outline-variant/30">
                <h2 class="text-base font-bold font-display text-on-surface">Data Akun &amp; Kontak</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Informasi utama untuk keperluan korespondensi seleksi kerja.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nama Lengkap <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Alamat Email <span class="text-rose-600">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                {{-- No WhatsApp / Telepon --}}
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nomor WhatsApp / HP
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="Contoh: 081234567890"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                {{-- Pendidikan Terakhir --}}
                <div>
                    <label for="education" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Pendidikan Terakhir
                    </label>
                    <input type="text" id="education" name="education" value="{{ old('education', $profile->education) }}" placeholder="Contoh: S1 Teknik Informatika"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/30">
                <h2 class="text-base font-bold font-display text-on-surface">Latar Belakang &amp; Berkas</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Informasi kompetensi dan berkas pendukung lamaran Anda.</p>
            </div>

            {{-- Alamat Domisili --}}
            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Alamat Domisili Saat Ini
                </label>
                <textarea id="address" name="address" rows="2" placeholder="Alamat lengkap tempat tinggal saat ini"
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('address', $profile->address) }}</textarea>
            </div>

            {{-- Ringkasan Keahlian --}}
            <div>
                <label for="skills" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Ringkasan Keahlian / Skill Utama
                </label>
                <textarea id="skills" name="skills" rows="3" placeholder="Contoh: Manajemen Operasional, Microsoft Excel Terampil, Analisis Data, Bahasa Inggris Aktif"
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('skills', $profile->skills) }}</textarea>
            </div>

            {{-- Upload CV Standar --}}
            <div class="p-5 rounded-xl bg-surface-container-low border border-outline-variant/30 space-y-2">
                <label for="cv" class="block text-xs font-bold uppercase tracking-wider text-on-surface">
                    Unggah / Ganti Berkas CV (PDF, Maks. 2MB)
                </label>
                <p class="text-xs text-on-surface-variant">
                    CV ini akan disimpan di profil utama dan digunakan otomatis untuk mempercepat proses melamar.
                </p>
                <input type="file" id="cv" name="cv" accept=".pdf,application/pdf"
                       class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-container cursor-pointer">
                @if ($profile->cv_path)
                    <div class="flex items-center gap-2 pt-2 text-xs text-emerald-700">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>Anda telah memiliki berkas CV tersimpan. Unggah berkas baru hanya jika ingin menggantinya.</span>
                    </div>
                @endif
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                <a href="{{ route('seeker.profile.show') }}" class="px-5 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm hover:bg-surface-variant border border-outline-variant/30 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
