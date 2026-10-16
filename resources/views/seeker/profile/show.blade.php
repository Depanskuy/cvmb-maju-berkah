@extends('layouts.seeker')

@section('title', 'Profil Saya — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-5xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sky-600">info</span>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Profil Saya
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Kelola informasi biodata pribadi, riwayat pendidikan, keahlian, dan dokumen CV utama Anda.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @if ($profile)
                <a href="{{ route('seeker.profile.edit') }}" class="px-4 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    <span>Edit Profil</span>
                </a>
            @else
                <a href="{{ route('seeker.profile.create') }}" class="px-4 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Buat Profil</span>
                </a>
            @endif
            <a href="{{ route('seeker.applications') }}" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface font-semibold text-sm flex items-center gap-2 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                <span class="material-symbols-outlined text-[18px]">assignment</span>
                <span>Lamaran Saya</span>
            </a>
        </div>
    </div>

    @if ($profile)
        {{-- Profile Card Header --}}
        <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary text-on-primary flex items-center justify-center font-display font-bold text-2xl shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl font-bold font-display text-on-surface">{{ $user->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Pencari Kerja Aktif
                        </span>
                    </div>
                    <p class="text-sm text-on-surface-variant mt-0.5 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">mail</span>
                        <span>{{ $user->email }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <a href="{{ route('seeker.profile.edit') }}" class="w-full md:w-auto px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-medium text-xs flex items-center justify-center gap-1.5 hover:bg-surface-variant border border-outline-variant/30 transition-all">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                    <span>Ubah Biodata</span>
                </a>
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Personal & Contact Info --}}
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col gap-4">
                <div class="flex items-center gap-2 pb-3 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[20px]">contact_phone</span>
                    <h3 class="font-bold text-sm font-display text-on-surface">Kontak &amp; Pendidikan</h3>
                </div>

                <div class="space-y-4 text-sm">
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama Lengkap</div>
                        <div class="font-medium text-on-surface mt-0.5">{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Email Akun</div>
                        <div class="font-medium text-on-surface mt-0.5">{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nomor WhatsApp / HP</div>
                        <div class="font-medium text-on-surface mt-0.5">
                            {{ $profile->phone ?: 'Belum diisi' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Pendidikan Terakhir</div>
                        <div class="font-medium text-on-surface mt-0.5">
                            {{ $profile->education ?: 'Belum diisi' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alamat Domisili & Keahlian --}}
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col gap-4">
                <div class="flex items-center gap-2 pb-3 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
                    <h3 class="font-bold text-sm font-display text-on-surface">Domisili &amp; Ringkasan Keahlian</h3>
                </div>

                <div class="space-y-4 text-sm">
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Alamat Domisili</div>
                        <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 text-on-surface mt-1 text-xs sm:text-sm">
                            {{ $profile->address ?: 'Belum ada alamat domisili tersimpan.' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Keahlian &amp; Keterampilan</div>
                        <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 text-on-surface mt-1 text-xs sm:text-sm whitespace-pre-line">
                            {{ $profile->skills ?: 'Belum ada daftar keahlian tersimpan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dokumen CV Tersimpan --}}
        <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30 flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">description</span>
                    <h3 class="font-bold text-sm font-display text-on-surface">Dokumen Curriculum Vitae (CV) Standar</h3>
                </div>
                <a href="{{ route('seeker.profile.edit') }}" class="text-xs font-semibold text-primary hover:underline">
                    Perbarui Berkas
                </a>
            </div>

            <div class="mt-4">
                @if ($profile->cv_path)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low border border-outline-variant/30 flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">picture_as_pdf</span>
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-on-surface">Curriculum_Vitae_Profil.pdf</div>
                                <div class="text-xs text-on-surface-variant">Dokumen terlampir aktif di profil Anda</div>
                            </div>
                        </div>
                        <a href="{{ route('seeker.profile.cv') }}" class="px-4 py-2 rounded-lg bg-white border border-outline-variant/40 text-primary font-semibold text-xs flex items-center gap-1.5 hover:bg-surface-container-high transition-all shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span>Unduh CV</span>
                        </a>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/60 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-amber-600">warning</span>
                            <div class="text-xs text-amber-900">
                                Anda belum mengunggah dokumen CV standar pada profil ini. CV mempermudah pengisian lamaran kerja otomatis.
                            </div>
                        </div>
                        <a href="{{ route('seeker.profile.edit') }}" class="px-3 py-1.5 rounded-lg bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-all">
                            Unggah Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Danger Zone: Hapus Profil (DELETE) --}}
        <div class="p-6 rounded-2xl bg-surface-container-lowest border border-rose-200/60 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-sm text-rose-700 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                    <span>Hapus Data Profil</span>
                </h4>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Menghapus biodata profil dan berkas CV dari sistem. Akun login Anda tetap aktif.
                </p>
            </div>
            <form method="POST" action="{{ route('seeker.profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data profil dan berkas CV Anda? Data yang dihapus tidak dapat dipulihkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-rose-50 text-rose-700 border border-rose-300 font-semibold text-xs flex items-center gap-1.5 hover:bg-rose-100 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                    <span>Hapus Profil</span>
                </button>
            </form>
        </div>

    @else
        {{-- Empty State --}}
        <div class="p-12 rounded-2xl bg-surface-container-lowest border border-dashed border-outline-variant/60 text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-surface-container-high text-on-surface-variant flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">person_add</span>
            </div>
            <h3 class="font-bold text-lg font-display text-on-surface">Profil Belum Dibuat</h3>
            <p class="text-sm text-on-surface-variant max-w-md mx-auto mt-1 mb-6">
                Anda belum melengkapi data profil dan CV. Lengkapi profil sekarang untuk mempermudah proses pengiriman berkas lamaran kerja.
            </p>
            <a href="{{ route('seeker.profile.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm hover:bg-primary-container shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Buat Profil Sekarang</span>
            </a>
        </div>
    @endif

</div>
@endsection
