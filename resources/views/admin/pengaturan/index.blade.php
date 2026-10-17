@extends('layouts.admin')

@section('title', 'Pengaturan Sistem — Admin CV Maju Berkah')

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

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-2">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <span>Terdapat kesalahan pengisian:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs pl-2">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Pengaturan Sistem &amp; Profil Perusahaan
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Kelola identitas resmi perusahaan, visi-misi, layanan, dan kontak korespondensi.
            </p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="p-6 sm:p-8 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data" class="space-y-6">
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
                    <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Slug <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $company->slug) }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="contact_email" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Alamat Email Resmi <span class="text-rose-600">*</span>
                    </label>
                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}" required
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="contact_phone" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nomor Telepon Kantor
                    </label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $company->contact_phone) }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>
            </div>

            <div>
                <label for="tagline" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Tagline Perusahaan
                </label>
                <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $company->tagline) }}"
                       placeholder="Bertumbuh Bersama, Melangkah Lebih Maju."
                       class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Deskripsi Singkat Perusahaan <span class="text-rose-600">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('description', $company->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="vision" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Visi
                    </label>
                    <textarea id="vision" name="vision" rows="4"
                              class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('vision', $company->vision) }}</textarea>
                </div>

                <div>
                    <label for="mission" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Misi
                    </label>
                    <textarea id="mission" name="mission" rows="4"
                              class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('mission', $company->mission) }}</textarea>
                </div>
            </div>

            <div>
                <label for="services" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Layanan Utama
                </label>
                <textarea id="services" name="services" rows="3"
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('services', $company->services) }}</textarea>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Alamat Kantor Pusat
                </label>
                <textarea id="address" name="address" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('address', $company->address) }}</textarea>
            </div>

            <div>
                <label for="logo" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Logo Perusahaan (PNG/JPG/WEBP/SVG, Maks. 2 MB)
                </label>
                @if ($company->logo_path)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="Logo perusahaan" class="h-12 w-auto rounded-lg border border-outline-variant/30">
                        <span class="text-xs text-on-surface-variant">{{ $company->logo_path }}</span>
                    </div>
                @endif
                <input type="file" id="logo" name="logo" accept="image/*"
                       class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-container cursor-pointer">
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
