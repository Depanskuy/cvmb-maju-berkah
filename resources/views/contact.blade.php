@extends('layouts.public')

@section('title', 'Kontak Kami — CV Maju Sukses')

@section('content')

{{-- HERO --}}
<section class="section-page" style="padding-top: 3rem; padding-bottom: 2rem;">
    <div class="container-page">
        <div style="display:flex; flex-direction:column; gap:0.75rem; max-width:48rem;">
            <div style="display:flex; flex-wrap:wrap; align-items:center; gap:0.75rem;">
                <nav class="page-hero__breadcrumb" style="color:var(--on-surface-variant); margin:0;">
                    <a href="{{ route('home') }}" style="color:inherit;">
                        <span class="material-symbols-outlined">home</span>
                        Beranda
                    </a>
                    <span class="page-hero__breadcrumb-sep" style="color:var(--outline-variant);">/</span>
                    <span style="color:var(--primary); font-weight:600;">Kontak Kami</span>
                </nav>
                <span style="display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.75rem; border-radius:9999px; background:rgba(254,174,44,0.2); color:var(--on-secondary-container); font-size:12px; font-weight:600;">
                    <span style="width:0.5rem; height:0.5rem; border-radius:50%; background:var(--secondary-container);"></span>
                    Respon Cepat SLA &lt; 24 Jam Kerja
                </span>
            </div>

            <h1 class="page-hero__title page-hero__title--lg" style="color:var(--primary); max-width:none;">
                Hubungi Tim Konsultan Ahli Kami
            </h1>
            <p style="font-size:18px; line-height:1.7; color:var(--on-surface-variant); max-width:42rem; margin:0;">
                Diskusikan tantangan bisnis, inisiatif transformasi, atau kerja sama korporasi bersama mitra terpercaya bersertifikasi resmi.
            </p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:1.5rem; margin-top:2rem; padding:1rem; background:rgba(241,243,255,0.7); border-radius:0.75rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">15 Menit</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Fast WhatsApp SLA</span>
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">100%</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Kerahasiaan NDA</span>
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">ISO 27001</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Tata Kelola Data</span>
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">SCBD Lot 28</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Jakarta Selatan HQ</span>
            </div>
        </div>
    </div>
</section>

{{-- MAIN --}}
<section class="section-page section-page--bg-surface" style="padding-top: 2rem;">
    <div class="container-page">
        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;" class="contact-main-grid">

            {{-- FORM --}}
            <div class="contact-form-card">
                <div class="contact-form-card__header">
                    <div>
                        <span style="font-size:12px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--primary);">Formulir Korporasi</span>
                        <h2 class="contact-form-card__title">Permintaan Konsultasi &amp; Kemitraan</h2>
                    </div>
                    <div class="contact-form-card__icon">
                        <span class="material-symbols-outlined">assignment</span>
                    </div>
                </div>

                @if (session('success_contact'))
                    <div style="margin-bottom:1.5rem; padding:1.25rem; border-radius:0.75rem; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; display:flex; align-items:flex-start; gap:0.75rem;">
                        <span class="material-symbols-outlined" style="color:#059669; font-size:24px;">check_circle</span>
                        <div>
                            <strong style="font-family:var(--font-display); font-size:15px; display:block;">Permintaan Kemitraan Berhasil Dikirim!</strong>
                            <p style="font-size:13px; margin:0.25rem 0 0;">{{ session('success_contact') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div style="margin-bottom:1.5rem; padding:1rem; border-radius:0.75rem; background:#fff1f2; border:1px solid #fecdd3; color:#9f1239; font-size:13px;">
                        <ul style="margin:0; padding-left:1.25rem;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.partnership') }}" style="display:flex; flex-direction:column; gap:1rem;" id="consultation-form">
                    @csrf

                    <div class="contact-form-grid">
                        <div class="contact-field">
                            <label class="contact-field__label" for="pic_name">
                                Nama Lengkap PIC <span class="contact-field__required">*</span>
                            </label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">person</span>
                                <input class="contact-field__input" id="pic_name" name="contact_name" value="{{ old('contact_name') }}" placeholder="Contoh: Budi Santoso" required type="text"/>
                            </div>
                        </div>
                        <div class="contact-field">
                            <label class="contact-field__label" for="corporate_email">
                                Email Resmi Perusahaan <span class="contact-field__required">*</span>
                            </label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">mail</span>
                                <input class="contact-field__input" id="corporate_email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.co.id" required type="email"/>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form-grid">
                        <div class="contact-field">
                            <label class="contact-field__label" for="pic_phone">
                                Nomor Telepon / WhatsApp PIC <span class="contact-field__required">*</span>
                            </label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">phone</span>
                                <input class="contact-field__input" id="pic_phone" name="phone" value="{{ old('phone') }}" placeholder="+62 812-XXXX-XXXX" required type="tel"/>
                            </div>
                        </div>
                        <div class="contact-field">
                            <label class="contact-field__label" for="company_name">
                                Nama Perusahaan / Instansi <span class="contact-field__required">*</span>
                            </label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">corporate_fare</span>
                                <input class="contact-field__input" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="PT Mega Graha Sentosa" required type="text"/>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form-grid">
                        <div class="contact-field">
                            <label class="contact-field__label" for="service_interest">
                                Pilih Minat Layanan <span class="contact-field__required">*</span>
                            </label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">tune</span>
                                <select class="contact-field__select" id="service_interest" name="service_interest" required>
                                    <option disabled selected value="">Pilih Layanan Konsultasi</option>
                                    <option value="Subkontraktor Sipil & MEP" {{ old('service_interest') === 'Subkontraktor Sipil & MEP' ? 'selected' : '' }}>Subkontraktor Sipil &amp; MEP</option>
                                    <option value="Penyedia Material Konstruksi" {{ old('service_interest') === 'Penyedia Material Konstruksi' ? 'selected' : '' }}>Penyedia Material Konstruksi</option>
                                    <option value="Transformasi Digital & IT" {{ old('service_interest') === 'Transformasi Digital & IT' ? 'selected' : '' }}>Transformasi Digital &amp; IT Enterprise</option>
                                    <option value="Jasa Konsultansi K3 & Lingkungan" {{ old('service_interest') === 'Jasa Konsultansi K3 & Lingkungan' ? 'selected' : '' }}>Jasa Konsultansi K3 &amp; Lingkungan</option>
                                    <option value="Lainnya" {{ old('service_interest') === 'Lainnya' ? 'selected' : '' }}>Layanan Kemitraan Lainnya</option>
                                </select>
                                <span class="material-symbols-outlined contact-field__arrow">expand_more</span>
                            </div>
                        </div>
                        <div class="contact-field">
                            <label class="contact-field__label" for="project_timeline">Estimasi Skala Proyek / Waktu</label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">schedule</span>
                                <select class="contact-field__select" id="project_timeline" name="project_timeline">
                                    <option value="Sangat Mendesak (< 1 Bulan)">Sangat Mendesak (&lt; 1 Bulan)</option>
                                    <option value="Kuartal Mendatang (1 - 3 Bulan)" selected>Kuartal Mendatang (1 - 3 Bulan)</option>
                                    <option value="Inisiatif Strategis Jangka Menengah (3 - 6 Bulan)">Inisiatif Strategis Jangka Menengah (3 - 6 Bulan)</option>
                                    <option value="Eksplorasi Pendahuluan / Studi Kelayakan">Eksplorasi Pendahuluan / Studi Kelayakan</option>
                                </select>
                                <span class="material-symbols-outlined contact-field__arrow">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-field">
                        <label class="contact-field__label" for="project_details" style="justify-content:space-between;">
                            <span>Pesan / Detail Kebutuhan Bisnis <span class="contact-field__required">*</span></span>
                            <span style="font-size:12px; color:var(--outline); font-weight:400;">Min. 10 karakter</span>
                        </label>
                        <textarea class="contact-field__textarea" id="project_details" name="project_details" placeholder="Jelaskan secara ringkas tantangan saat ini, tujuan akhir transformasi, ruang lingkup unit kerja, dan ekspektasi capaian..." required rows="4">{{ old('project_details') }}</textarea>
                    </div>

                    <div class="contact-consent">
                        <input id="nda_agreement" required type="checkbox" checked/>
                        <label for="nda_agreement">
                            Saya menyetujui bahwa data ini dilindungi secara legal oleh <strong style="color:var(--primary);">Kebijakan Privasi</strong> dan standar kerahasiaan non-disclosure agreement (<strong style="color:var(--primary);">NDA</strong>) CV Maju Sukses.
                        </label>
                    </div>

                    <div class="contact-submit-row">
                        <button class="btn-page btn-page--dark cursor-pointer" id="submit-btn" type="submit">
                            <span>Kirim Pesan &amp; Jadwalkan Diskusi</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                        <div class="contact-submit-note">
                            <span class="material-symbols-outlined">verified_user</span>
                            <span>Enkripsi TLS 1.3 Terverifikasi</span>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SIDEBAR --}}
            <div class="contact-sidebar">

                <div class="whatsapp-card">
                    <div class="whatsapp-card__header">
                        <span class="whatsapp-card__pill">Saluran Cepat Prioritas</span>
                        <span class="whatsapp-card__status">
                            <span class="whatsapp-card__status-dot"></span>
                            Online Sekarang
                        </span>
                    </div>
                    <h3 class="whatsapp-card__title">Konsultasi Singkat via WhatsApp</h3>
                    <p class="whatsapp-card__desc">
                        Butuh respons kilat mengenai ketersediaan jadwal auditor atau proposal awal? Hubungi desk representative kami dengan SLA tanggapan instan 15 menit.
                    </p>
                    <a class="whatsapp-card__btn" href="https://wa.me/628119876543" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">chat</span>
                        Chat WhatsApp (+62 811-9876-543)
                    </a>
                </div>

                <div class="office-card">
                    <div class="office-card__header">
                        <div class="office-card__title-group">
                            <div class="office-card__icon">
                                <span class="material-symbols-outlined">domain</span>
                            </div>
                            <div>
                                <h3 class="office-card__title">Kantor Pusat Jakarta</h3>
                                <span class="office-card__sub">Headquarters &amp; Executive Suites</span>
                            </div>
                        </div>
                        <span class="office-card__badge">SCBD</span>
                    </div>

                    <div class="office-card__list">
                        <div class="office-item">
                            <span class="material-symbols-outlined">location_on</span>
                            <div>
                                <div class="office-item__label">Alamat Fisik</div>
                                <p class="office-item__value">{{ $company->address ?? 'Menara Sudirman Lt. 18, SCBD Lot 28, Jakarta Selatan 12190' }}</p>
                            </div>
                        </div>
                        <div class="office-item">
                            <span class="material-symbols-outlined">query_builder</span>
                            <div>
                                <div class="office-item__label">Jam Operasional</div>
                                <p class="office-item__value" style="font-weight:500;">Senin – Jumat: 08.30 – 17.30 WIB</p>
                                <span style="font-size:12px; color:var(--outline);">Sabtu, Minggu &amp; Libur Nasional Tutup</span>
                            </div>
                        </div>
                        <div class="office-item">
                            <span class="material-symbols-outlined">alternate_email</span>
                            <div>
                                <div class="office-item__label">Email Korespondensi</div>
                                <p class="office-item__value"><a href="mailto:{{ $company->contact_email ?? 'corporate@majusukses.co.id' }}">{{ $company->contact_email ?? 'corporate@majusukses.co.id' }}</a></p>
                            </div>
                        </div>
                        <div class="office-item">
                            <span class="material-symbols-outlined">call</span>
                            <div>
                                <div class="office-item__label">Telepon Kantor &amp; Hotline</div>
                                <p class="office-item__value"><a href="tel:+62215558900">{{ $company->contact_phone ?? '+62 (21) 555-8900' }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="trust-card">
                    <div class="trust-card__left">
                        <div class="trust-card__icon">
                            <span class="material-symbols-outlined">lock</span>
                        </div>
                        <div>
                            <p class="trust-card__title">Enkripsi SSL 256-Bit</p>
                            <p class="trust-card__sub">Standar Kepatuhan ISO 27001 Teruji</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined trust-card__check">verified</span>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- STEPS --}}
<section class="container-page" style="padding-bottom: 3rem;">
    <div class="steps-strip">
        @php
            $steps = [
                ['n' => '1', 'title' => 'Tinjauan Awal Portofolio',   'desc' => 'Tim analis senior kami mempelajari data tantangan bisnis Anda dalam batas kerahasiaan ketat.'],
                ['n' => '2', 'title' => 'Discovery Session 1-on-1',   'desc' => 'Sesi diskusi strategis daring via Zoom/Teams atau pertemuan langsung di kantor pusat SCBD.'],
                ['n' => '3', 'title' => 'Penyusunan Proposal Kerja',  'desc' => 'Penyusunan roadmap terukur, estimasi timeline, dan struktur alokasi konsultan spesialis.'],
            ];
        @endphp
        @foreach ($steps as $s)
            <div class="step-item">
                <div class="step-item__num">{{ $s['n'] }}</div>
                <div>
                    <h4 class="step-item__title">{{ $s['title'] }}</h4>
                    <p class="step-item__desc">{{ $s['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

@endsection

@push('styles')
<style>
    @media (min-width: 1024px) {
        .contact-main-grid { grid-template-columns: 7fr 5fr !important; align-items: start; }
    }
</style>
@endpush

@push('scripts')
<script>
function handleConsultationSubmit() {
    const btn = document.getElementById('submit-btn');
    const alertBox = document.getElementById('form-success-alert');
    const form = document.getElementById('consultation-form');

    if (btn) {
        btn.disabled = true;
        btn.style.opacity = '0.75';
        btn.innerHTML = '<span class="material-symbols-outlined">progress_activity</span><span>Mengirimkan Permintaan...</span>';
    }

    setTimeout(() => {
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.innerHTML = '<span>Kirim Pesan &amp; Jadwalkan Diskusi</span><span class="material-symbols-outlined">arrow_forward</span>';
        }
        if (alertBox) {
            alertBox.style.display = 'flex';
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        if (form) form.reset();
    }, 1200);
}
</script>
@endpush