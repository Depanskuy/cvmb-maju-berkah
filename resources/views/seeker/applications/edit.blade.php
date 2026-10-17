@extends('layouts.seeker')

@section('title', 'Ubah Lamaran #' . $application->id . ' — CV Maju Berkah')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-4xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('seeker.applications') }}" class="hover:text-primary transition-colors">Lamaran Saya</a>
                <span>/</span>
                <a href="{{ route('seeker.applications.show', $application) }}" class="hover:text-primary transition-colors">ID #{{ $application->id }}</a>
                <span>/</span>
                <span class="text-on-surface font-semibold">Ubah Lamaran</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Ubah Berkas Lamaran
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Posisi: <strong class="text-primary">{{ $application->jobListing->title ?? '-' }}</strong>
                &bull; Lamaran hanya dapat diubah selama status masih <strong>Menunggu</strong>.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('seeker.applications.show', $application) }}" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm flex items-center gap-2 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- Alert Errors --}}
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

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Edit Form Card --}}
    <div class="p-6 sm:p-8 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
        <form method="POST" action="{{ route('seeker.applications.update', $application) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pb-4 border-b border-outline-variant/30">
                <h2 class="text-base font-bold font-display text-on-surface">Perbarui Data Lamaran</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Data akun (nama &amp; email) terkunci pada akun login Anda. Anda dapat mengubah pendidikan, keahlian, surat lamaran, dan berkas CV.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="education" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Pendidikan Terakhir
                    </label>
                    <input type="text" id="education" name="education" value="{{ old('education', $application->education) }}" placeholder="Contoh: S1 Manajemen"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                </div>

                <div>
                    <label for="applicant_phone_display" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Nomor WhatsApp / HP (Terkunci)
                    </label>
                    <input type="text" id="applicant_phone_display" value="{{ $application->applicant_phone }}" readonly
                           class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/30 bg-surface-container-low text-on-surface-variant text-sm cursor-not-allowed outline-none">
                </div>
            </div>

            <div>
                <label for="skills" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Keahlian &amp; Pengalaman yang Relevan
                </label>
                <textarea id="skills" name="skills" rows="3" placeholder="Sebutkan kompetensi teknis, sertifikasi, atau pengalaman kerja Anda..."
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('skills', $application->skills) }}</textarea>
            </div>

            <div>
                <label for="cover_letter" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Surat Lamaran / Catatan Pengantar
                </label>
                <textarea id="cover_letter" name="cover_letter" rows="4" placeholder="Ceritakan motivasi Anda melamar posisi ini..."
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant/50 bg-surface-container-lowest text-on-surface text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y">{{ old('cover_letter', $application->cover_letter) }}</textarea>
            </div>

            <div class="p-5 rounded-xl bg-surface-container-low border border-outline-variant/30 space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface">
                    Dokumen Curriculum Vitae (CV) (PDF, Maks. 2MB)
                </label>

                @if ($application->cv_path)
                    <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-outline-variant/30">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <div class="flex-1 text-xs">
                            <span class="font-semibold text-on-surface">CV Saat Ini Tersimpan</span>
                            <span class="text-on-surface-variant block">Biarkan kosong jika tidak ingin mengganti berkas CV.</span>
                        </div>
                        <a href="{{ route('seeker.applications.cv', $application) }}" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">download</span>
                            <span>Unduh</span>
                        </a>
                    </div>
                @endif

                <div>
                    <label for="cv" class="block text-xs text-on-surface-variant mb-1">
                        {{ $application->cv_path ? 'Unggah CV pengganti (opsional):' : 'Pilih berkas CV PDF Anda:' }}
                    </label>
                    <input type="file" id="cv" name="cv" accept=".pdf,application/pdf"
                           class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-container cursor-pointer">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                <a href="{{ route('seeker.applications.show', $application) }}" class="px-5 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm hover:bg-surface-variant border border-outline-variant/30 transition-all">
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
