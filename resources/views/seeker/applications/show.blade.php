@extends('layouts.seeker')

@section('title', 'Detail Lamaran #' . $application->id . ' — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-5xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('seeker.applications') }}" class="hover:underline">Lamaran Saya</a>
                <span>•</span>
                <span class="text-primary font-semibold">ID Lamaran #{{ $application->id }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                {{ $application->jobListing->title ?? 'Detail Posisi Lowongan' }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            @if ($application->cv_path)
                <a href="{{ route('seeker.applications.cv', $application) }}" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-primary hover:bg-primary hover:text-white transition-colors flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    <span>Unduh CV Saya</span>
                </a>
            @endif
            <a href="{{ route('seeker.applications') }}" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- 2-Column Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Informasi Pelamar & Lowongan (2 cols) --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Ringkasan Lowongan --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex flex-col gap-4">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-outline-variant/20">
                    <div>
                        <div class="text-xs uppercase font-semibold text-on-surface-variant">Posisi Pekerjaan</div>
                        <div class="text-lg font-bold text-primary font-display mt-0.5">
                            {{ $application->jobListing->title ?? 'Posisi Tidak Tersedia' }}
                        </div>
                        <div class="text-xs text-on-surface-variant">
                            Divisi {{ $application->jobListing->category ?? '-' }} • {{ $application->jobListing->employment_type_label ?? '-' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-on-surface-variant">Tanggal Diajukan</div>
                        <div class="text-sm font-semibold text-on-surface">{{ $application->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Nama Pelamar</span>
                        <span class="font-bold text-on-surface">{{ $application->applicant_name }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Alamat Email</span>
                        <span class="text-on-surface">{{ $application->applicant_email }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">No. WhatsApp / HP</span>
                        <span class="text-on-surface">{{ $application->applicant_phone ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Pendidikan Terakhir</span>
                        <span class="text-on-surface">{{ $application->education ?? '-' }}</span>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold text-on-surface-variant block uppercase mb-1">Alamat Domisili</span>
                    <span class="text-sm text-on-surface bg-surface-container-low px-3 py-2 rounded-lg block">
                        {{ $application->applicant_address ?: 'Tidak dicantumkan' }}
                    </span>
                </div>

                <div>
                    <span class="text-xs font-semibold text-on-surface-variant block uppercase mb-1">Daftar Keahlian Utama</span>
                    <span class="text-sm text-on-surface bg-surface-container-low px-3 py-2 rounded-lg block">
                        {{ $application->skills ?: 'Tidak dicantumkan' }}
                    </span>
                </div>
            </div>

            {{-- Cover Letter --}}
            @if ($application->cover_letter)
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex flex-col gap-3">
                    <h2 class="font-bold text-base text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">mail</span>
                        <span>Surat Lamaran Singkat</span>
                    </h2>
                    <div class="text-sm text-on-surface/90 leading-relaxed bg-surface-container-low/70 p-4 rounded-xl whitespace-pre-line border border-outline-variant/10">
                        {{ $application->cover_letter }}
                    </div>
                </div>
            @endif

            {{-- Dokumen CV --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
                        <span class="material-symbols-outlined text-[28px]">picture_as_pdf</span>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-on-surface">Curriculum Vitae (CV) Yang Dikirim</div>
                        <div class="text-xs text-on-surface-variant">Berkas dokumen PDF resmi lamaran</div>
                    </div>
                </div>
                @if ($application->cv_path)
                    <a href="{{ route('seeker.applications.cv', $application) }}" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        <span>Unduh Berkas</span>
                    </a>
                @else
                    <span class="text-xs text-on-surface-variant italic">Berkas tidak tersedia</span>
                @endif
            </div>

        </div>

        {{-- Right: Progres Seleksi & Catatan HR (1 col) --}}
        <div class="flex flex-col gap-6">

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex flex-col gap-4">
                <h2 class="font-bold text-base text-on-surface flex items-center gap-2 pb-3 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-[20px] text-primary">timeline</span>
                    <span>Status Seleksi Lamaran</span>
                </h2>

                <div>
                    <span class="text-xs font-semibold text-on-surface-variant block uppercase mb-1">Status Saat Ini</span>
                    @php
                        $badgeClass = match($application->status) {
                            'pending'    => 'bg-amber-100 text-amber-800 border-amber-200',
                            'processing' => 'bg-sky-100 text-sky-800 border-sky-200',
                            'interview'  => 'bg-purple-100 text-purple-800 border-purple-200',
                            'accepted'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'rejected'   => 'bg-rose-100 text-rose-800 border-rose-200',
                            default      => 'bg-slate-100 text-slate-800 border-slate-200',
                        };
                        if ($application->isWithdrawn()) {
                            $badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                        }
                    @endphp
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full border {{ $badgeClass }}">
                        {{ $application->status_label }}
                    </span>
                    @if ($application->isWithdrawn())
                        <p class="text-xs text-rose-600 mt-2">
                            Lamaran ini telah dibatalkan pada {{ $application->withdrawn_at->format('d M Y H:i') }}.
                        </p>
                    @endif
                </div>

                {{-- Catatan Review HR jika ada --}}
                @if ($application->review_note)
                    <div class="p-3.5 rounded-xl bg-primary/5 border border-primary/20 text-xs">
                        <div class="font-bold text-primary flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-[16px]">feedback</span>
                            <span>Catatan Tim Rekrutmen:</span>
                        </div>
                        <p class="text-on-surface/90 leading-relaxed m-0">{{ $application->review_note }}</p>
                    </div>
                @else
                    <div class="text-xs text-on-surface-variant/80 italic p-3 rounded-lg bg-surface-container-low">
                        Belum ada catatan tambahan dari tim HR.
                    </div>
                @endif

                {{-- Aksi Pembatalan jika masih Pending --}}
                @if ($application->canBeWithdrawn())
                    <div class="pt-4 border-t border-outline-variant/15">
                        <form method="POST" action="{{ route('seeker.applications.withdraw', $application) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan lamaran ini? Tindakan ini tidak dapat dibatalkan.');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 font-semibold text-xs hover:bg-rose-100 transition-colors flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                                <span>Batalkan Lamaran Ini</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Info Lowongan Karir Link --}}
            @if ($application->jobListing)
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5 text-xs text-on-surface-variant flex flex-col gap-2">
                    <div class="font-bold text-on-surface text-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-primary">work</span>
                        <span>Informasi Lowongan</span>
                    </div>
                    <p class="text-on-surface-variant">
                        Batas lamaran: <strong>{{ $application->jobListing->deadline->format('d F Y') }}</strong>
                    </p>
                    <a href="{{ route('careers.show', $application->jobListing) }}" class="inline-flex items-center gap-1 text-primary font-semibold hover:underline mt-1">
                        <span>Lihat Deskripsi Lengkap Lowongan</span>
                        <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                    </a>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
