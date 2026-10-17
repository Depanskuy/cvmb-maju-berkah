@extends('layouts.public')

@section('title', 'Kontak Kami — CV Maju Berkah')

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
                    Respon Cepat &lt; 24 Jam Kerja
                </span>
            </div>

            <h1 class="page-hero__title page-hero__title--lg" style="color:var(--primary); max-width:none;">
                Hubungi Tim CV Maju Berkah
            </h1>
            <p style="font-size:18px; line-height:1.7; color:var(--on-surface-variant); max-width:42rem; margin:0;">
                Diskusikan kebutuhan pengadaan barang, pasokan rutin, atau kerja sama kemitraan usaha bersama tim kami.
            </p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:1.5rem; margin-top:2rem; padding:1rem; background:rgba(241,243,255,0.7); border-radius:0.75rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">1x24 Jam</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Respon Permintaan</span>
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">2.000+</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Mitra Usaha Aktif</span>
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">ISO 9001</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Standar Mutu</span>
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="font-family:var(--font-display); font-size:22px; font-weight:700; color:var(--primary);">Jakarta</span>
                <span style="font-size:12px; font-weight:600; color:var(--on-surface-variant); text-transform:uppercase; letter-spacing:0.04em;">Kantor Pusat</span>
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
                        <span style="font-size:12px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--primary);">Formulir Kemitraan</span>
                        <h2 class="contact-form-card__title">Permintaan Pengadaan &amp; Kemitraan</h2>
                    </div>
                    <div class="contact-form-card__icon">
                        <span class="material-symbols-outlined">assignment</span>
                    </div>
                </div>

                @if (session('success_contact'))
                    <div style="margin-bottom:1.5rem; padding:1.25rem; border-radius:0.75rem; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; display:flex; align-items:flex-start; gap:0.75rem;">
                        <span class="material-symbols-outlined" style="color:#059669; font-size:24px;">check_circle</span>
                        <div>
                            <strong style="font-family:var(--font-display); font-size:15px; display:block;">Permintaan Berhasil Dikirim!</strong>
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
                                Pilih Jenis Layanan <span class="contact-field__required">*</span>
                            </label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">tune</span>
                                <select class="contact-field__select" id="service_interest" name="service_interest" required>
                                    <option disabled selected value="">Pilih Jenis Layanan</option>
                                    <option value="Pasokan Barang Rutin" {{ old('service_interest') === 'Pasokan Barang Rutin' ? 'selected' : '' }}>Pasokan Barang Rutin</option>
                                    <option value="Kebutuhan Usaha & Perlengkapan Kantor" {{ old('service_interest') === 'Kebutuhan Usaha & Perlengkapan Kantor' ? 'selected' : '' }}>Kebutuhan Usaha &amp; Perlengkapan Kantor</option>
                                    <option value="Pengadaan Massal / Tender" {{ old('service_interest') === 'Pengadaan Massal / Tender' ? 'selected' : '' }}>Pengadaan Massal / Tender</option>
                                    <option value="Kerja Sama Distribusi & Keagenan" {{ old('service_interest') === 'Kerja Sama Distribusi & Keagenan' ? 'selected' : '' }}>Kerja Sama Distribusi &amp; Keagenan</option>
                                    <option value="Lainnya" {{ old('service_interest') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <span class="material-symbols-outlined contact-field__arrow">expand_more</span>
                            </div>
                        </div>
                        <div class="contact-field">
                            <label class="contact-field__label" for="project_timeline">Perkiraan Waktu Kebutuhan</label>
                            <div class="contact-field__wrap">
                                <span class="material-symbols-outlined contact-field__icon">schedule</span>
                                <select class="contact-field__select" id="project_timeline" name="project_timeline">
                                    <option value="Segera (< 1 Bulan)">Segera (&lt; 1 Bulan)</option>
                                    <option value="1 - 3 Bulan" selected>1 - 3 Bulan</option>
                                    <option value="3 - 6 Bulan">3 - 6 Bulan</option>
                                    <option value="Sekadar Informasi / Penawaran">Sekadar Informasi / Penawaran</option>
                                </select>
                                <span class="material-symbols-outlined contact-field__arrow">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-field">
                        <label class="contact-field__label" for="project_details" style="justify-content:space-between;">
                            <span>Pesan / Detail Kebutuhan <span class="contact-field__required">*</span></span>
                            <span style="font-size:12px; color:var(--outline); font-weight:400;">Min. 10 karakter</span>
                        </label>
                        <textarea class="contact-field__textarea" id="project_details" name="project_details" placeholder="Jelaskan jenis barang yang dibutuhkan, perkiraan jumlah, lokasi pengiriman, dan waktu kebutuhan..." required rows="4">{{ old('project_details') }}</textarea>
                    </div>

                    <div class="contact-consent">
                        <input id="nda_agreement" required type="checkbox" checked/>
                        <label for="nda_agreement">
                            Saya menyetujui bahwa data ini dikelola sesuai <strong style="color:var(--primary);">Kebijakan Privasi</strong> dan dijaga kerahasiaannya oleh CV Maju Berkah.
                        </label>
                    </div>

                    <div class="contact-submit-row">
                        <button class="btn-page btn-page--dark cursor-pointer" id="submit-btn" type="submit">
                            <span>Kirim Permintaan</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                        <div class="contact-submit-note">
                            <span class="material-symbols-outlined">verified_user</span>
                            <span>Data Terkirim dengan Aman</span>
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
                    <h3 class="whatsapp-card__title">Tanya Cepat via WhatsApp</h3>
                    <p class="whatsapp-card__desc">
                        Butuh informasi ketersediaan barang, harga, atau jadwal pengiriman? Hubungi tim layanan kami dengan respons cepat pada jam kerja.
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
                                <span class="material-symbols-outlined">warehouse</span>
                            </div>
                            <div>
                                <h3 class="office-card__title">Kantor Pusat &amp; Gudang</h3>
                                <span class="office-card__sub">Jakarta</span>
                            </div>
                        </div>
                        <span class="office-card__badge">Pusat</span>
                    </div>

                    <div class="office-card__list">
                        <div class="office-item">
                            <span class="material-symbols-outlined">location_on</span>
                            <div>
                                <div class="office-item__label">Alamat Fisik</div>
                                <p class="office-item__value">{{ $company->address ?? 'Jl. Gatot Subroto No. 12, Jakarta Selatan 12930' }}</p>
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
                                <p class="office-item__value"><a href="mailto:{{ $company->contact_email ?? 'info@majuberkah.co.id' }}">{{ $company->contact_email ?? 'info@majuberkah.co.id' }}</a></p>
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
                            <p class="trust-card__title">Data Anda Aman</p>
                            <p class="trust-card__sub">Standar Kerahasiaan &amp; Kendali Mutu</p>
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
                ['n' => '1', 'title' => 'Permintaan Diterima',   'desc' => 'Tim kami menerima detail kebutuhan Anda beserta informasi jumlah dan lokasi pengiriman.'],
                ['n' => '2', 'title' => 'Konfirmasi & Penawaran','desc' => 'Kami memverifikasi ketersediaan stok dan mengirimkan penawaran harga serta estimasi waktu pengiriman.'],
                ['n' => '3', 'title' => 'Pesanan Diproses',       'desc' => 'Setelah kesepakatan, pesanan disiapkan, diperiksa kualitasnya, dan dikirim ke lokasi Anda.'],
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
            btn.innerHTML = '<span>Kirim Permintaan</span><span class="material-symbols-outlined">arrow_forward</span>';
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
