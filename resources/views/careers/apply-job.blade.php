@extends('layouts.public')

@section('title', 'Lamar Pekerjaan — CV Maju Sukses')

@section('content')
<section class="career-main">
    <div class="container-public" style="max-width: 860px;">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('careers.show', $jobListing) }}" class="section-link" style="margin-bottom: 1.5rem; display: inline-flex;">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>

        <article class="apply-card">
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
                    <h1 class="apply-card__title">Form Lamaran Pekerjaan</h1>
                    <p class="apply-card__subtitle">
                        Lengkapi formulir untuk melamar posisi
                        {{ $jobListing->title }}.
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
                                   value="{{ old('applicant_name', auth()->user()->name ?? '') }}"
                                   required>
                            @error('applicant_name')
                                <span class="apply-field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="apply-field">
                            <label for="applicant_email" class="apply-field__label">
                                Alamat Email Akun <span class="apply-field__required">*</span>
                            </label>
                            <input type="email"
                                   name="applicant_email"
                                   id="applicant_email"
                                   class="apply-field__input @error('applicant_email') is-invalid @enderror"
                                   style="background-color: var(--surface-container-high); cursor: not-allowed;"
                                   value="{{ old('applicant_email', auth()->user()->email ?? '') }}"
                                   readonly
                                   required>
                            <span style="font-size: 11px; color: var(--on-surface-variant); margin-top: 2px;">
                                Terhubung dengan akun login Anda
                            </span>
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
                                   value="{{ old('applicant_phone', auth()->user()->jobSeekerProfile->phone ?? '') }}"
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
                                   value="{{ old('education', auth()->user()->jobSeekerProfile->education ?? '') }}">
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
                               value="{{ old('applicant_address', auth()->user()->jobSeekerProfile->address ?? '') }}">
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
                                  placeholder="Contoh: Microsoft Excel, Komunikasi, Manajemen Waktu">{{ old('skills', auth()->user()->jobSeekerProfile->skills ?? '') }}</textarea>
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
