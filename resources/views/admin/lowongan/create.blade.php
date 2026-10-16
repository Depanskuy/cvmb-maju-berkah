@extends('layouts.admin')

@section('title', 'Tambah Lowongan Baru — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-4xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('admin.lowongan.index') }}" class="hover:underline">Lowongan</a>
                <span>•</span>
                <span class="text-primary font-semibold">Tambah Baru</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Buat Lowongan Pekerjaan Baru
            </h1>
        </div>
        <a href="{{ route('admin.lowongan.index') }}" class="px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
            Kembali
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="font-bold mb-1">Terdapat kesalahan pengisian data:</div>
            <ul class="list-disc pl-5 text-xs space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.lowongan.store') }}" class="flex flex-col gap-6">
            @csrf

            {{-- Row: Judul & Kategori --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Judul Posisi Lowongan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Site Project Engineer"
                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div>
                    <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Kategori / Divisi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" required placeholder="Contoh: Teknik &amp; Konstruksi, Operasional"
                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </div>

            {{-- Row: Tipe, Lokasi, Deadline --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="employment_type" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Tipe Pekerjaan <span class="text-rose-500">*</span>
                    </label>
                    <select id="employment_type" name="employment_type" required
                            class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="full_time" {{ old('employment_type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu (Full Time)</option>
                        <option value="part_time" {{ old('employment_type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu (Part Time)</option>
                        <option value="internship" {{ old('employment_type') === 'internship' ? 'selected' : '' }}>Magang (Internship)</option>
                        <option value="contract" {{ old('employment_type') === 'contract' ? 'selected' : '' }}>Kontrak (Contract)</option>
                    </select>
                </div>

                <div>
                    <label for="location" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Lokasi Penempatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="Contoh: Jakarta Selatan"
                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div>
                    <label for="deadline" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Batas Akhir Lamaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" required
                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </div>

            {{-- Row: Gaji Min, Gaji Max, Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="salary_min" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Gaji Minimal (Rp)
                    </label>
                    <input type="number" id="salary_min" name="salary_min" value="{{ old('salary_min') }}" placeholder="Contoh: 5000000" min="0" step="100000"
                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div>
                    <label for="salary_max" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Gaji Maksimal (Rp)
                    </label>
                    <input type="number" id="salary_max" name="salary_max" value="{{ old('salary_max') }}" placeholder="Contoh: 8000000" min="0" step="100000"
                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Status Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif (Tampil &amp; Bisa Dilamar)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draf (Hanya Admin)</option>
                        <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
            </div>

            {{-- Deskripsi Pekerjaan --}}
            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Deskripsi Pekerjaan &amp; Tanggung Jawab <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required placeholder="Tuliskan gambaran umum posisi dan tanggung jawab sehari-hari..."
                          class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description') }}</textarea>
            </div>

            {{-- Kualifikasi & Persyaratan --}}
            <div>
                <label for="requirements" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Kualifikasi &amp; Persyaratan Kandidat <span class="text-rose-500">*</span>
                </label>
                <textarea id="requirements" name="requirements" rows="4" required placeholder="Tuliskan latar belakang pendidikan, keahlian khusus, pengalaman kerja..."
                          class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('requirements') }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/20">
                <a href="{{ route('admin.lowongan.index') }}" class="px-5 py-2.5 rounded-lg bg-surface-container-high text-sm font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm shadow-sm hover:bg-primary-container transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan &amp; Publikasikan</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
