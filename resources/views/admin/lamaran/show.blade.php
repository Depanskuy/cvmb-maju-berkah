@extends('layouts.admin')

@section('title', 'Review Lamaran: ' . $application->applicant_name . ' — Admin CV Maju Sukses')

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

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Breadcrumb & Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-1">
                <a href="{{ route('admin.lamaran.index') }}" class="hover:underline">Lamaran Masuk</a>
                <span>•</span>
                <span class="text-primary font-semibold">Review Lamaran #{{ $application->id }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Berkas Lamaran: {{ $application->applicant_name }}
            </h1>
        </div>
        <a href="{{ route('admin.lamaran.index') }}" class="px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
            Kembali ke Daftar
        </a>
    </div>

    {{-- 2-Column Grid: Left (Applicant details) & Right (Status Action Box) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Applicant Details (2 cols) --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Summary Card --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex flex-col gap-4">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-outline-variant/20">
                    <div>
                        <div class="text-xs uppercase font-semibold text-on-surface-variant">Posisi Yang Dilamar</div>
                        <div class="text-lg font-bold text-primary font-display mt-0.5">
                            {{ $application->jobListing->title ?? 'Posisi Tidak Ada' }}
                        </div>
                        <div class="text-xs text-on-surface-variant">
                            Divisi {{ $application->jobListing->category ?? '-' }} • {{ $application->jobListing->employment_type_label ?? '-' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-on-surface-variant">Tanggal Pengajuan</div>
                        <div class="text-sm font-semibold text-on-surface">{{ $application->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Nama Pelamar</span>
                        <span class="font-bold text-on-surface">{{ $application->applicant_name }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Email</span>
                        <a href="mailto:{{ $application->applicant_email }}" class="text-primary hover:underline font-medium">
                            {{ $application->applicant_email }}
                        </a>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Nomor Telepon / WA</span>
                        <span class="font-medium text-on-surface">{{ $application->applicant_phone ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-on-surface-variant block uppercase">Pendidikan Terakhir</span>
                        <span class="font-medium text-on-surface">{{ $application->education ?? '-' }}</span>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold text-on-surface-variant block uppercase mb-1">Alamat Domisili</span>
                    <span class="text-sm text-on-surface bg-surface-container-low px-3 py-2 rounded-lg block">
                        {{ $application->applicant_address ?: 'Tidak dicantumkan' }}
                    </span>
                </div>

                <div>
                    <span class="text-xs font-semibold text-on-surface-variant block uppercase mb-1">Ringkasan Keahlian</span>
                    <span class="text-sm text-on-surface bg-surface-container-low px-3 py-2 rounded-lg block">
                        {{ $application->skills ?: 'Tidak dicantumkan' }}
                    </span>
                </div>
            </div>

            {{-- Cover Letter --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex flex-col gap-3">
                <h2 class="font-bold text-base text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-primary">mail</span>
                    <span>Surat Lamaran / Pengantar</span>
                </h2>
                <div class="text-sm text-on-surface/90 leading-relaxed bg-surface-container-low/70 p-4 rounded-xl whitespace-pre-line border border-outline-variant/10">
                    {{ $application->cover_letter ?: 'Pelamar tidak menyertakan surat lamaran khusus.' }}
                </div>
            </div>

            {{-- CV Download Card --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
                        <span class="material-symbols-outlined text-[28px]">picture_as_pdf</span>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-on-surface">Curriculum Vitae (CV) Kandidat</div>
                        <div class="text-xs text-on-surface-variant">Dokumen PDF resmi pelamar</div>
                    </div>
                </div>
                @if ($application->cv_path)
                    <a href="{{ route('admin.lamaran.cv', $application) }}" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        <span>Unduh Berkas CV</span>
                    </a>
                @else
                    <span class="text-xs text-rose-500 italic">Berkas tidak tersedia</span>
                @endif
            </div>

        </div>

        {{-- Right: Status Updater & HR Notes (1 col) --}}
        <div class="flex flex-col gap-6">

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6 flex flex-col gap-4">
                <h2 class="font-bold text-base text-on-surface flex items-center gap-2 pb-3 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-[20px] text-primary">edit_note</span>
                    <span>Kelola Status Seleksi</span>
                </h2>

                {{-- Status saat ini --}}
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
                            Lamaran ini telah dibatalkan oleh pelamar pada {{ $application->withdrawn_at->format('d M Y H:i') }}.
                        </p>
                    @endif
                </div>

                {{-- Form update status --}}
                @if (! $application->isWithdrawn())
                    <form method="POST" action="{{ route('admin.lamaran.status.update', $application) }}" class="flex flex-col gap-4 mt-2">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                                Ubah Status Lamaran <span class="text-rose-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <option value="pending" {{ old('status', $application->status) === 'pending' ? 'selected' : '' }}>
                                    Menunggu Review (Pending)
                                </option>
                                <option value="processing" {{ old('status', $application->status) === 'processing' ? 'selected' : '' }}>
                                    Sedang Diproses (Screening)
                                </option>
                                <option value="interview" {{ old('status', $application->status) === 'interview' ? 'selected' : '' }}>
                                    Lolos Tahap Interview
                                </option>
                                <option value="accepted" {{ old('status', $application->status) === 'accepted' ? 'selected' : '' }}>
                                    Diterima Bergabung (Accepted)
                                </option>
                                <option value="rejected" {{ old('status', $application->status) === 'rejected' ? 'selected' : '' }}>
                                    Tidak Lolos / Ditolak (Rejected)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="review_note" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1.5">
                                Catatan / Feedback untuk Pelamar
                            </label>
                            <textarea id="review_note" name="review_note" rows="4" placeholder="Contoh: Selamat, Anda dijadwalkan interview pada hari Senin pukul 09.00 WIB melalui Google Meet. Tautan dikirim ke email."
                                      class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface border border-transparent focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('review_note', $application->review_note) }}</textarea>
                            <span class="text-[11px] text-on-surface-variant/80 block mt-1">
                                Catatan ini akan langsung terlihat oleh pelamar di menu <strong>Lamaran Saya</strong> miliknya.
                            </span>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-primary text-on-primary font-semibold text-sm shadow-sm hover:bg-primary-container transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">update</span>
                            <span>Perbarui Status</span>
                        </button>
                    </form>
                @endif
            </div>

            {{-- Info Akun --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5 text-xs text-on-surface-variant flex flex-col gap-2">
                <div class="font-bold text-on-surface text-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px] text-primary">account_box</span>
                    <span>Status Kepemilikan Akun</span>
                </div>
                @if ($application->user_id)
                    <p class="text-emerald-700 bg-emerald-50 p-2 rounded-lg border border-emerald-200">
                        Lamaran ini terhubung dengan akun terdaftar <strong>{{ $application->user->name ?? 'User' }}</strong> (ID #{{ $application->user_id }}).
                    </p>
                @else
                    <p class="text-slate-600 bg-slate-100 p-2 rounded-lg border border-slate-200">
                        Lamaran lama (dikirim sebelum sistem autentikasi diwajibkan). Tidak terhubung ke akun mana pun.
                    </p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
