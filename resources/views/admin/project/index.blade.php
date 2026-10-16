@extends('layouts.admin')

@section('title', 'Manajemen Project — Admin CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6 max-w-7xl mx-auto">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display tracking-tight text-on-surface">
                Manajemen Project &amp; Portofolio
            </h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Monitoring pelaksanaan proyek lapangan, update progres fisik (%), dan status pengerjaan CV Maju Sukses.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button type="button" onclick="openCreateProjectModal()"
                    class="px-4 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-sm flex items-center gap-2 hover:bg-primary-container shadow-sm transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tambah Project Baru</span>
            </button>
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

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-on-surface-variant uppercase">Total Project</div>
            <div class="text-2xl font-bold font-display text-on-surface mt-1">{{ $stats['total_projects'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-sky-700 uppercase">Sedang Berjalan</div>
            <div class="text-2xl font-bold font-display text-sky-600 mt-1">{{ $stats['ongoing'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-700 uppercase">Selesai</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['completed'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm">
            <div class="text-xs font-semibold text-amber-700 uppercase">Tahap Persiapan</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $stats['planning'] }}</div>
        </div>
    </div>

    {{-- Filters & Search Toolbar --}}
    <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.project.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama project, klien, kategori..."
                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <select name="status"
                    class="px-3 py-2 bg-surface-container-low rounded-lg text-sm text-on-surface border-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Status</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Tahap Persiapan</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer">
                Terapkan
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.project.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Project Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse ($projects as $prj)
            <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary/10 text-primary">
                            {{ $prj->category }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $prj->status_badge }}">
                            {{ $prj->status_label }}
                        </span>
                    </div>

                    <h2 class="text-lg font-bold font-display text-on-surface">
                        {{ $prj->name }}
                    </h2>

                    <div class="space-y-1.5 text-xs text-on-surface-variant">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">apartment</span>
                            <span>Klien: <strong>{{ $prj->client }}</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">calendar_month</span>
                            <span>Jadwal: {{ $prj->timeline }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">account_balance_wallet</span>
                            <span>Nilai Kontrak: <strong class="text-on-surface">{{ $prj->budget }}</strong></span>
                        </div>
                        @if ($prj->description)
                            <p class="text-xs text-on-surface-variant/80 pt-1 border-t border-outline-variant/15 line-clamp-2">
                                {{ $prj->description }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Progress Bar & Action Controls --}}
                <div class="pt-3 border-t border-outline-variant/20 space-y-3">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-on-surface-variant font-medium">Progress Fisik Proyek</span>
                            <span class="font-bold text-primary">{{ $prj->progress }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                            <div class="h-full rounded-full bg-primary transition-all duration-500" style="width: {{ $prj->progress }}%;"></div>
                        </div>
                    </div>

                    {{-- Actions: Update Progress & Delete --}}
                    <div class="flex items-center justify-between gap-2 pt-1">
                        <button type="button"
                                onclick="openUpdateProjectModal({{ json_encode([
                                    'id'          => $prj->id,
                                    'name'        => $prj->name,
                                    'client'      => $prj->client,
                                    'category'    => $prj->category,
                                    'budget'      => $prj->budget,
                                    'timeline'    => $prj->timeline,
                                    'progress'    => $prj->progress,
                                    'status'      => $prj->status,
                                    'description' => $prj->description,
                                    'update_url'  => route('admin.project.update', $prj->id),
                                ]) }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-semibold hover:bg-primary hover:text-on-primary transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-[15px]">sync_alt</span>
                            <span>Update Progres &amp; Status</span>
                        </button>

                        <form method="POST" action="{{ route('admin.project.destroy', $prj->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini dari portofolio? Tindakan ini tidak dapat dibatalkan.');" class="inline m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Proyek">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center bg-surface-container-lowest rounded-2xl border border-outline-variant/30 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">folder_off</span>
                Tidak ada project yang cocok dengan filter pencarian Anda.
            </div>
        @endforelse
    </div>

</div>

{{-- Modal Tambah Project Baru --}}
<div id="createProjectModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xl w-full max-w-xl overflow-hidden animate-fadeIn">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">domain_add</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg font-display text-on-surface">Tambah Project Baru</h3>
                    <p class="text-xs text-on-surface-variant">Daftarkan proyek baru ke sistem portofolio CV Maju Sukses.</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateProjectModal()" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.project.store') }}">
            @csrf

            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Nama Project <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Pembangunan Gudang Logistik Modern"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Klien / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="client" required placeholder="Contoh: PT Sumber Mitra Sejahtera"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Kategori Bidang <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="Konstruksi Bangunan">Konstruksi Bangunan</option>
                            <option value="Mekanikal & Elektrikal">Mekanikal &amp; Elektrikal</option>
                            <option value="Fabrikasi Baja">Fabrikasi Baja</option>
                            <option value="Infrastruktur & Sipil">Infrastruktur &amp; Sipil</option>
                            <option value="Pengadaan & Maintenance">Pengadaan &amp; Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Nilai Kontrak / Anggaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="budget" required placeholder="Contoh: Rp 3.5 Miliar"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Jadwal Pengerjaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="timeline" required placeholder="Contoh: Feb 2026 - Nov 2026"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Progres Awal Lapangan (%)
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="progress" min="0" max="100" value="0" id="createProgressRange"
                                   oninput="document.getElementById('createProgressValue').textContent = this.value + '%'"
                                   class="w-full accent-primary cursor-pointer">
                            <span id="createProgressValue" class="font-bold text-sm text-primary w-12 text-right">0%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Status Proyek <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="planning">Tahap Persiapan</option>
                            <option value="ongoing" selected>Sedang Berjalan</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Deskripsi / Spesifikasi Ringkas (Opsional)
                    </label>
                    <textarea name="description" rows="2" placeholder="Catatan teknis, luasan area, kapasitas, dsb."
                              class="w-full px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
            </div>

            <div class="p-4 px-6 border-t border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateProjectModal()" class="px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Simpan Project</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Update Progres Lapangan & Status --}}
<div id="updateProjectModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xl w-full max-w-xl overflow-hidden animate-fadeIn">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">engineering</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg font-display text-on-surface" id="updateProjectTitle">Update Progres Lapangan</h3>
                    <p class="text-xs text-on-surface-variant">Perbarui realisasi fisik pengerjaan proyek dari lapangan.</p>
                </div>
            </div>
            <button type="button" onclick="closeUpdateProjectModal()" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="updateProjectForm" method="POST" action="">
            @csrf
            @method('PUT')

            <input type="hidden" name="name" id="editName">
            <input type="hidden" name="client" id="editClient">
            <input type="hidden" name="category" id="editCategory">

            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                {{-- Field Progres Slider --}}
                <div class="p-4 rounded-xl bg-surface-container-low space-y-2 border border-outline-variant/20">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-on-surface uppercase tracking-wider">
                            Realisasi Progres Fisik (%)
                        </label>
                        <span id="updateProgressDisplay" class="font-bold text-xl text-primary font-display">0%</span>
                    </div>

                    <input type="range" name="progress" min="0" max="100" id="updateProgressSlider"
                           oninput="updateSliderVal(this.value)"
                           class="w-full accent-primary h-2 bg-surface-container-high rounded-lg cursor-pointer">

                    <div class="flex items-center justify-between gap-2 pt-1 text-[11px] text-on-surface-variant">
                        <button type="button" onclick="setFastProgress(25)" class="px-2 py-0.5 rounded bg-surface-container-high hover:bg-primary/20">25%</button>
                        <button type="button" onclick="setFastProgress(50)" class="px-2 py-0.5 rounded bg-surface-container-high hover:bg-primary/20">50%</button>
                        <button type="button" onclick="setFastProgress(75)" class="px-2 py-0.5 rounded bg-surface-container-high hover:bg-primary/20">75%</button>
                        <button type="button" onclick="setFastProgress(100)" class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold hover:bg-emerald-200">100% (Selesai)</button>
                    </div>
                </div>

                {{-- Status Select --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Status Pengerjaan <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="editStatus" required class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="planning">Tahap Persiapan</option>
                        <option value="ongoing">Sedang Berjalan</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Nilai Kontrak
                        </label>
                        <input type="text" name="budget" id="editBudget" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                            Jadwal Pengerjaan
                        </label>
                        <input type="text" name="timeline" id="editTimeline" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Catatan Teknis &amp; Lapangan
                    </label>
                    <textarea name="description" id="editDescription" rows="2"
                              class="w-full px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
            </div>

            <div class="p-4 px-6 border-t border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeUpdateProjectModal()" class="px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Simpan Pembaruan Progres</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateProjectModal() {
    document.getElementById('createProjectModal').classList.remove('hidden');
}

function closeCreateProjectModal() {
    document.getElementById('createProjectModal').classList.add('hidden');
}

function openUpdateProjectModal(data) {
    document.getElementById('updateProjectTitle').textContent = 'Update Progres: ' + data.name;
    document.getElementById('editName').value = data.name;
    document.getElementById('editClient').value = data.client;
    document.getElementById('editCategory').value = data.category;
    document.getElementById('editBudget').value = data.budget;
    document.getElementById('editTimeline').value = data.timeline;
    document.getElementById('editStatus').value = data.status;
    document.getElementById('editDescription').value = data.description || '';

    updateSliderVal(data.progress);

    document.getElementById('updateProjectForm').action = data.update_url;
    document.getElementById('updateProjectModal').classList.remove('hidden');
}

function closeUpdateProjectModal() {
    document.getElementById('updateProjectModal').classList.add('hidden');
}

function updateSliderVal(val) {
    document.getElementById('updateProgressSlider').value = val;
    document.getElementById('updateProgressDisplay').textContent = val + '%';
    if (val == 100) {
        document.getElementById('editStatus').value = 'completed';
    }
}

function setFastProgress(val) {
    updateSliderVal(val);
}
</script>
@endsection
