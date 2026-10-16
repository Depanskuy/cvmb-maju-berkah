{{-- resources/views/admin/lowongan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Lowongan — CV Maju Sukses')

@section('content')
<div class="flex flex-col w-full gap-6">

    {{-- STATS STRIP --}}
    @include('partials.admin.stats-strip')

    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col">
            <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight">Manajemen Lowongan Kerja</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Kelola, buat, dan pantau status lowongan karir perusahaan di website</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button type="button" class="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container-lowest text-primary-container font-title-md text-title-md shadow-sm hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[19px]">file_download</span>
                <span>Export Excel</span>
            </button>
            <a href="{{ route('admin.lowongan.create') ?? '#' }}" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary font-title-md text-title-md shadow-sm hover:bg-primary transition-all">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span>Tambah Lowongan Baru</span>
            </a>
        </div>
    </div>

    {{-- TOOLBAR --}}
    @include('partials.admin.lowongan-toolbar')

    {{-- TABLE --}}
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden flex flex-col">
        @include('partials.admin.lowongan-table', ['jobs' => $jobs ?? []])
        @include('partials.admin.lowongan-pagination', ['jobs' => $jobs ?? null])
    </div>

</div>
@endsection