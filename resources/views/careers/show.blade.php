@extends('layouts.public')

@section('title', $jobListing->title . ' — CV Maju Sukses')

@section('content')

{{-- ============ HERO ============ --}}
<section class="career-hero">
    <div class="career-hero__glow-1"></div>
    <div class="career-hero__glow-2"></div>

    <div class="container-public career-hero__inner">

        {{-- Breadcrumb --}}
        <nav class="career-hero__breadcrumb">
            <a href="{{ route('careers.index') }}">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Kembali ke Lowongan</span>
            </a>
            <span class="career-hero__breadcrumb-sep">•</span>
            <span class="career-hero__breadcrumb-current">Divisi {{ $jobListing->category }}</span>
        </nav>

        <div class="career-hero__flex">

            {{-- Left --}}
            <div>
                <div class="career-hero__badges">

                    @if ($jobListing->isOpenForApplication())
                        <span class="career-hero__badge career-hero__badge--green">
                            <span class="career-hero__badge-dot"></span>
                            {{ $jobListing->employment_type_label }}
                        </span>
                    @else
                        <span class="career-hero__badge career-hero__badge--neutral">
                            {{ $jobListing->status_label }}
                        </span>
                    @endif

                    <span class="career-hero__badge career-hero__badge--neutral">
                        {{ $jobListing->category }}
                    </span>

                    <span class="career-hero__badge career-hero__badge--gold">
                        Prioritas Perekrutan
                    </span>
                </div>

                <h1 class="career-hero__title">{{ $jobListing->title }}</h1>

                <div class="career-hero__meta">
                    <div class="career-hero__meta-item">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>{{ $jobListing->location }}</span>
                    </div>
                    <div class="career-hero__meta-item">
                        <span class="material-symbols-outlined">event</span>
                        <span>Batas Lamaran: {{ $jobListing->deadline->format('d M Y') }}</span>
                    </div>
                    @if ($jobListing->salary_min && $jobListing->salary_max)
                        <div class="career-hero__meta-item">
                            <span class="material-symbols-outlined">payments</span>
                            <span>Rp {{ number_format($jobListing->salary_min, 0, ',', '.') }} - {{ number_format($jobListing->salary_max, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: CTA --}}
            <div class="career-hero__cta">
                @if ($jobListing->isOpenForApplication())
                    <a href="#apply-form" class="career-hero__btn-primary">
                        <span>Lamar Sekarang</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                @else
                    <span class="career-hero__btn-primary" style="opacity:0.5; cursor:not-allowed;">
                        <span>Lowongan Ditutup</span>
                    </span>
                @endif

                <a href="{{ route('careers.index') }}" class="career-hero__btn-ghost">
                    <span class="material-symbols-outlined">list</span>
                    <span>Semua Lowongan</span>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ============ QUICK STRIP ============ --}}
<section class="career-strip">
    <div class="container-public">
        <div class="career-strip__inner">

            <div class="career-strip__left">
                <div class="career-strip__item">
                    <span class="material-symbols-outlined">badge</span>
                    <span>{{ $jobListing->employment_type_label }}</span>
                </div>
                <div class="career-strip__item">
                    <span class="material-symbols-outlined">schedule</span>
                    <span>Diposting {{ $jobListing->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="career-strip__right">
                <span class="career-strip__dot"></span>
                <span>
                    @if ($jobListing->isOpenForApplication())
                        Posisi aktif • Terbuka untuk dilamar
                    @else
                        Lowongan sudah ditutup
                    @endif
                </span>
            </div>

        </div>
    </div>
</section>

{{-- ============ MAIN ============ --}}
<section class="career-main">
    <div class="container-public">
        <div class="career-main__grid">

            {{-- ================= LEFT COLUMN ================= --}}
            <div class="career-content">

                {{-- Deskripsi --}}
                <article class="content-card">
                    <div class="content-card__accent"></div>
                    <h2 class="content-card__title">
                        <span class="material-symbols-outlined">description</span>
                        Deskripsi Pekerjaan
                    </h2>
                    <div class="content-card__body">
                        {!! nl2br(e($jobListing->description)) !!}
                    </div>
                </article>

                {{-- Kualifikasi --}}
                <article class="content-card">
                    <div class="content-card__accent content-card__accent--gold"></div>
                    <h2 class="content-card__title">
                        <span class="material-symbols-outlined">verified_user</span>
                        Kualifikasi &amp; Persyaratan
                    </h2>
                    <div class="content-card__body">
                        <p class="requirements-list">{{ $jobListing->requirements }}</p>
                    </div>
                </article>

                {{-- Info Tambahan --}}
                <article class="content-card">
                    <div class="content-card__accent"></div>
                    <h2 class="content-card__title">
                        <span class="material-symbols-outlined">info</span>
                        Informasi Tambahan
                    </h2>
                    <div class="content-card__body">
                        <div class="content-callout">
                            <span class="material-symbols-outlined">lightbulb</span>
                            <p>
                                <strong>Proses seleksi:</strong> Tim rekrutmen akan meninjau berkas Anda
                                dalam 3–5 hari kerja. Hanya kandidat yang lolos tahap awal yang akan dihubungi
                                untuk tahap selanjutnya.
                            </p>
                        </div>
                    </div>
                </article>

                {{-- ============ APPLICATION FORM ============ --}}
                <article class="apply-card" id="apply-form">

                    {{-- Success alert --}}
                    @if (session('apply_success'))
                        <div class="apply-alert apply-alert--success">
                            <span class="material-symbols-outlined">check_circle</span>
                            <div>
                                <p class="apply-alert__title">Lamaran Berhasil Terkirim!</p>
                                <p class="apply-alert__msg">{{ session('apply_success') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Error global --}}
                    @if ($errors->any())
                        <div class="apply-alert apply-alert--error">
                            <span class="material-symbols-outlined">error</span>
                            <div>
                                <p class="apply-alert__title">Ada kesalahan pada form Anda</p>
                                <ul style="margin: 0.25rem 0 0 1rem; padding: 0;">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Closed notice --}}
                    @if (! $jobListing->isOpenForApplication())
                        <div class="career-closed-notice">
                            <span class="material-symbols-outlined">event_busy</span>
                            <div>
                                <strong>Lowongan sudah ditutup.</strong>
                                Batas lamaran telah terlewati atau lowongan tidak menerima lamaran baru.
                            </div>
                        </div>
                    @endif

                    <div class="apply-card__header">
                        <div class="apply-card__icon">
                            <span class="material-symbols-outlined">send</span>
                        </div>
                        <div>
                            <h3 class="apply-card__title">Kirimkan Berkas Lamaran Anda</h3>
                            <p class="apply-card__subtitle">
                                Lengkapi formulir di bawah ini. Tim People &amp; Talent kami akan merespons dalam 3×24 jam.
                            </p>
                        </div>
                    </div>

                    @if ($jobListing->isOpenForApplication())
                        <form action="{{ route('careers.apply.store', $jobListing) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="apply-form"
                              id="job-application-form">
                            @csrf

                            {{-- Nama + Email --}}
                            <div class="apply-form__row">
                                <div class="apply-field">
                                    <label for="applicant_name" class="apply-field__label">
                                        Nama Lengkap <span class="apply-field__required">*</span>
                                    </label>
                                    <input type="text"
                                           name="applicant_name"
                                           id="applicant_name"
                                           class="apply-field__input @error('applicant_name') is-invalid @enderror"
                                           placeholder="Contoh: Budi Santoso"
                                           value="{{ old('applicant_name') }}"
                                           required>
                                    @error('applicant_name')
                                        <span class="apply-field__error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="apply-field">
                                    <label for="applicant_email" class="apply-field__label">
                                        Alamat Email Aktif <span class="apply-field__required">*</span>
                                    </label>
                                    <input type="email"
                                           name="applicant_email"
                                           id="applicant_email"
                                           class="apply-field__input @error('applicant_email') is-invalid @enderror"
                                           placeholder="budi@example.com"
                                           value="{{ old('applicant_email') }}"
                                           required>
                                    @error('applicant_email')
                                        <span class="apply-field__error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone + Pendidikan --}}
                            <div class="apply-form__row">
                                <div class="apply-field">
                                    <label for="applicant_phone" class="apply-field__label">
                                        Nomor WhatsApp Aktif <span class="apply-field__required">*</span>
                                    </label>
                                    <input type="tel"
                                           name="applicant_phone"
                                           id="applicant_phone"
                                           class="apply-field__input @error('applicant_phone') is-invalid @enderror"
                                           placeholder="+62 812-3456-7890"
                                           value="{{ old('applicant_phone') }}"
                                           required>
                                    @error('applicant_phone')
                                        <span class="apply-field__error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="apply-field">
                                    <label for="education" class="apply-field__label">Pendidikan Terakhir</label>
                                    <input type="text"
                                           name="education"
                                           id="education"
                                           class="apply-field__input @error('education') is-invalid @enderror"
                                           placeholder="Contoh: S1 Manajemen"
                                           value="{{ old('education') }}">
                                    @error('education')
                                        <span class="apply-field__error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="apply-field">
                                <label for="applicant_address" class="apply-field__label">Alamat Domisili</label>
                                <input type="text"
                                       name="applicant_address"
                                       id="applicant_address"
                                       class="apply-field__input @error('applicant_address') is-invalid @enderror"
                                       placeholder="Contoh: Jl. Merdeka No. 12, Jakarta Timur"
                                       value="{{ old('applicant_address') }}">
                                @error('applicant_address')
                                    <span class="apply-field__error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Skills --}}
                            <div class="apply-field">
                                <label for="skills" class="apply-field__label">Keahlian Utama</label>
                                <textarea name="skills"
                                          id="skills"
                                          rows="2"
                                          class="apply-field__textarea @error('skills') is-invalid @enderror"
                                          placeholder="Contoh: Microsoft Excel, Komunikasi, Manajemen Waktu">{{ old('skills') }}</textarea>
                                @error('skills')
                                    <span class="apply-field__error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Cover Letter --}}
                            <div class="apply-field">
                                <label for="cover_letter" class="apply-field__label">Surat Lamaran Singkat</label>
                                <textarea name="cover_letter"
                                          id="cover_letter"
                                          rows="4"
                                          class="apply-field__textarea @error('cover_letter') is-invalid @enderror"
                                          placeholder="Ceritakan singkat mengapa Anda tertarik dan cocok untuk posisi ini...">{{ old('cover_letter') }}</textarea>
                                @error('cover_letter')
                                    <span class="apply-field__error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- CV Upload --}}
                            <div class="apply-field">
                                <label class="apply-field__label">
                                    Unggah CV (PDF) <span class="apply-field__required">*</span>
                                </label>

                                <div class="apply-upload @error('cv') is-invalid @enderror"
                                     id="drop-area"
                                     onclick="document.getElementById('cv-upload').click()">
                                    <input type="file"
                                           name="cv"
                                           id="cv-upload"
                                           accept=".pdf,application/pdf"
                                           onchange="fileChosen(event)">
                                    <span class="material-symbols-outlined apply-upload__icon" id="upload-icon">upload_file</span>
                                    <p class="apply-upload__title" id="file-label-text">
                                        Klik untuk memilih file atau seret ke sini
                                    </p>
                                    <p class="apply-upload__hint">Format PDF, maksimal 2 MB</p>
                                </div>
                                @error('cv')
                                    <span class="apply-field__error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Consent --}}
                            <div class="apply-consent">
                                <input type="checkbox" name="consent" id="consent" value="1" required>
                                <label for="consent">
                                    Saya menyatakan bahwa data yang diisi akurat dan memberikan izin
                                    kepada Tim Rekrutmen CV Maju Sukses untuk memproses data pribadi saya
                                    sesuai kebijakan privasi perusahaan.
                                </label>
                            </div>

                            {{-- Submit --}}
                            <div>
                                <button type="submit" class="apply-submit" id="btn-submit-app">
                                    <span class="material-symbols-outlined">send</span>
                                    <span>Kirim Lamaran Sekarang</span>
                                </button>
                            </div>

                        </form>
                    @endif

                </article>

            </div>

            {{-- ================= RIGHT SIDEBAR ================= --}}
            <aside class="career-sidebar">

                {{-- Ringkasan --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card__title">
                        <span class="material-symbols-outlined">info</span>
                        Ringkasan Lowongan
                    </h3>

                    <div class="summary-list">

                        <div class="summary-row">
                            <span class="material-symbols-outlined">badge</span>
                            <div>
                                <div class="summary-row__label">Tipe Pekerjaan</div>
                                <div class="summary-row__value">{{ $jobListing->employment_type_label }}</div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span class="material-symbols-outlined">location_on</span>
                            <div>
                                <div class="summary-row__label">Lokasi</div>
                                <div class="summary-row__value">{{ $jobListing->location }}</div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span class="material-symbols-outlined">domain</span>
                            <div>
                                <div class="summary-row__label">Kategori</div>
                                <div class="summary-row__value">{{ $jobListing->category }}</div>
                            </div>
                        </div>

                        @if ($jobListing->salary_min && $jobListing->salary_max)
                            <div class="summary-row">
                                <span class="material-symbols-outlined">payments</span>
                                <div>
                                    <div class="summary-row__label">Rentang Gaji</div>
                                    <div class="summary-row__value summary-row__value--primary">
                                        Rp {{ number_format($jobListing->salary_min, 0, ',', '.') }} - {{ number_format($jobListing->salary_max, 0, ',', '.') }}
                                    </div>
                                    <div class="summary-row__sub">Per bulan</div>
                                </div>
                            </div>
                        @endif

                        <div class="summary-row">
                            <span class="material-symbols-outlined">event_busy</span>
                            <div>
                                <div class="summary-row__label">Batas Lamaran</div>
                                <div class="summary-row__value {{ $jobListing->isOpenForApplication() ? '' : 'summary-row__value--danger' }}">
                                    {{ $jobListing->deadline->format('d F Y') }}
                                </div>
                            </div>
                        </div>

                    </div>

                    @if ($jobListing->isOpenForApplication())
                        <a href="#apply-form" class="sidebar-cta">
                            <span>Lamar Sekarang</span>
                            <span class="material-symbols-outlined">north_east</span>
                        </a>
                    @endif
                </div>

                {{-- Profil Perusahaan --}}
                <div class="sidebar-card">
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                        <div style="width:3rem; height:3rem; border-radius:0.75rem; background:var(--primary); color:#fff; font-family:var(--font-display); font-weight:700; display:flex; align-items:center; justify-content:center; font-size:1.125rem;">
                            MS
                        </div>
                        <div>
                            <div style="font-family:var(--font-display); font-size:18px; font-weight:700; color:var(--primary);">
                                {{ $company->name }}
                            </div>
                            <div style="display:flex; align-items:center; gap:0.25rem; font-size:12px; color:var(--on-surface-variant);">
                                <span class="material-symbols-outlined" style="font-size:16px; color:var(--primary);">verified</span>
                                <span>Perusahaan Resmi Terdaftar</span>
                            </div>
                        </div>
                    </div>

                    <p style="font-size:13px; line-height:1.6; color:var(--on-surface-variant); margin:0 0 1rem;">
                        {{ \Illuminate\Support\Str::limit($company->description, 180) }}
                    </p>

                    <a href="{{ route('about') }}" class="section-link">
                        <span>Pelajari Profil Perusahaan</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>

                {{-- Hubungi --}}
                <div class="sidebar-card">
                    <h4 class="sidebar-card__title" style="font-size:16px;">
                        <span class="material-symbols-outlined">contact_support</span>
                        Butuh Bantuan?
                    </h4>
                    <p style="font-size:13px; line-height:1.6; color:var(--on-surface-variant); margin:0 0 0.75rem;">
                        Ada pertanyaan seputar proses rekrutmen? Hubungi tim kami:
                    </p>
                    <div class="summary-row" style="border:none; padding:0;">
                        <span class="material-symbols-outlined">mail</span>
                        <div>
                            <div class="summary-row__value" style="font-size:14px; word-break:break-all;">
                                {{ $company->contact_email }}
                            </div>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Drag & drop CV upload
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('cv-upload');
    const labelText = document.getElementById('file-label-text');
    const uploadIcon = document.getElementById('upload-icon');

    if (dropArea && fileInput) {
        ['dragenter', 'dragover'].forEach(evt => {
            dropArea.addEventListener(evt, (e) => {
                e.preventDefault();
                dropArea.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(evt => {
            dropArea.addEventListener(evt, (e) => {
                e.preventDefault();
                dropArea.classList.remove('is-dragover');
            });
        });

        dropArea.addEventListener('drop', (e) => {
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateFileLabel(e.dataTransfer.files[0].name);
            }
        });
    }

    function fileChosen(e) {
        if (e.target.files && e.target.files.length > 0) {
            updateFileLabel(e.target.files[0].name);
        }
    }

    function updateFileLabel(name) {
        if (!labelText) return;
        labelText.textContent = 'File terpilih: ' + name;
        if (uploadIcon) uploadIcon.textContent = 'task';
        if (dropArea) dropArea.classList.add('has-file');
    }

    // Submit loading state
    const form = document.getElementById('job-application-form');
    const submitBtn = document.getElementById('btn-submit-app');

    if (form && submitBtn) {
        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined">refresh</span><span>Mengirim...</span>';
        });
    }
</script>
@endpush