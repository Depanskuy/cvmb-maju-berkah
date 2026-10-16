@extends('layouts.admin')

@section('title', 'Pengajuan Kerja Sama — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Pengajuan Kerja Sama &amp; Kemitraan
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Kelola permohonan kemitraan vendor, subkontraktor, dan kerja sama bisnis B2B CV Maju Sukses.
            </p>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Stats Cards Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-on-surface-variant uppercase">Total Pengajuan</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-amber-700 uppercase">Menunggu Review</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $stats['pending'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-700 uppercase">Disetujui / Aktif</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['approved'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-slate-600 uppercase">Arsip Kemitraan</div>
            <div class="text-2xl font-bold font-display text-slate-600 mt-1">{{ $stats['archived'] }}</div>
        </div>
    </div>

    {{-- Filters & Search Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.kerja-sama.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan, kontak, bidang..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="status"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui / Aktif</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Arsip Kemitraan</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.kerja-sama.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Proposals Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
        <div class="p-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-base font-display text-on-surface">Daftar Rekanan &amp; Proposal Masuk</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">Permohonan resmi yang diajukan oleh calon mitra usaha.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-primary/10 text-primary">
                {{ count($proposals) }} Pengajuan Ditampilkan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Perusahaan &amp; Kontak</th>
                        <th class="px-5 py-3.5">Bidang Kemitraan</th>
                        <th class="px-5 py-3.5">Waktu Pengajuan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Ringkasan Kebutuhan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15 text-on-surface">
                    @forelse ($proposals as $p)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-on-surface">{{ $p->company_name }}</div>
                                <div class="text-xs text-on-surface-variant">PIC: {{ $p->contact_name }} &bull; {{ $p->phone }}</div>
                                <div class="text-xs text-primary">{{ $p->email }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface">
                                    {{ $p->type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-on-surface-variant">
                                {{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $p->status_badge }}">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-on-surface-variant max-w-xs">
                                <div class="line-clamp-2">{{ $p->message }}</div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button type="button"
                                        onclick="openReviewModal({{ json_encode([
                                            'id'           => $p->id,
                                            'company_name' => $p->company_name,
                                            'contact_name' => $p->contact_name,
                                            'email'        => $p->email,
                                            'phone'        => $p->phone,
                                            'type'         => $p->type,
                                            'timeline'     => $p->timeline,
                                            'message'      => $p->message,
                                            'status'       => $p->status,
                                            'admin_note'   => $p->admin_note,
                                            'update_url'   => route('admin.kerja-sama.status.update', $p->id)
                                        ]) }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container transition-all cursor-pointer shadow-sm">
                                    <span class="material-symbols-outlined text-[15px]">rate_review</span>
                                    <span>Tinjau</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant text-sm">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">handshake</span>
                                Tidak ada proposal kemitraan yang cocok dengan kriteria pencarian Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Tinjau Proposal & Status Kemitraan --}}
<div id="reviewModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xl w-full max-w-2xl overflow-hidden animate-fadeIn">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">handshake</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg font-display text-on-surface" id="modalCompanyName">Detail Proposal Kemitraan</h3>
                    <p class="text-xs text-on-surface-variant">Tinjau informasi kebutuhan rekanan &amp; perbarui status tindak lanjut.</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="reviewForm" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                {{-- Data Rekanan Preview --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-surface-container-low text-xs">
                    <div>
                        <span class="text-on-surface-variant block font-medium">Penanggung Jawab (PIC)</span>
                        <span class="font-bold text-on-surface text-sm" id="modalContactName">-</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block font-medium">Bidang Layanan</span>
                        <span class="font-bold text-primary text-sm" id="modalType">-</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block font-medium">Email Perusahaan</span>
                        <span class="font-semibold text-on-surface" id="modalEmail">-</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block font-medium">No. Telepon / WhatsApp</span>
                        <span class="font-semibold text-on-surface" id="modalPhone">-</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-on-surface-variant block font-medium">Estimasi Timeline / Jadwal</span>
                        <span class="font-semibold text-on-surface" id="modalTimeline">-</span>
                    </div>
                </div>

                {{-- Pesan Kebutuhan --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">Detail Kebutuhan / Permohonan Mitra</label>
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface leading-relaxed whitespace-pre-line" id="modalMessage">
                        -
                    </div>
                </div>

                {{-- Status Selection --}}
                <div>
                    <label for="modalStatusSelect" class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Status Kemitraan <span class="text-rose-500">*</span>
                    </label>
                    <select id="modalStatusSelect" name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="pending">Menunggu Review</option>
                        <option value="approved">Disetujui / Aktif (Rekanan Resmi)</option>
                        <option value="archived">Arsip Kemitraan (Nonaktif / Ditangguhkan)</option>
                    </select>
                </div>

                {{-- Admin Note --}}
                <div>
                    <label for="modalAdminNote" class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Catatan Internal Admin (Opsional)
                    </label>
                    <textarea id="modalAdminNote" name="admin_note" rows="3" placeholder="Tuliskan catatan internal, misal: 'Sudah dihubungi via WA, menunggu pengiriman dokumen company profile...'"
                              class="w-full px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
            </div>

            <div class="p-4 px-6 border-t border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Simpan Keputusan &amp; Status</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal(data) {
    document.getElementById('modalCompanyName').textContent = data.company_name;
    document.getElementById('modalContactName').textContent = data.contact_name;
    document.getElementById('modalEmail').textContent = data.email;
    document.getElementById('modalPhone').textContent = data.phone;
    document.getElementById('modalType').textContent = data.type;
    document.getElementById('modalTimeline').textContent = data.timeline || 'Standar';
    document.getElementById('modalMessage').textContent = data.message;
    document.getElementById('modalStatusSelect').value = data.status;
    document.getElementById('modalAdminNote').value = data.admin_note || '';

    document.getElementById('reviewForm').action = data.update_url;
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
}
</script>
@endsection
