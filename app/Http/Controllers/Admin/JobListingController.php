<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobListingController extends Controller
{
    /**
     * Tampilkan daftar lowongan kerja di panel admin.
     * GET /admin/lowongan — route name: admin.lowongan.index
     */
    public function index(Request $request)
    {
        $query = JobListing::withCount('applications')->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('category', 'ILIKE', "%{$search}%")
                  ->orWhere('location', 'ILIKE', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter employment type
        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        $jobs = $query->paginate(10)->withQueryString();

        $stats = [
            'total'  => JobListing::count(),
            'active' => JobListing::where('status', JobListing::STATUS_ACTIVE)->count(),
            'draft'  => JobListing::where('status', JobListing::STATUS_DRAFT)->count(),
            'closed' => JobListing::where('status', JobListing::STATUS_CLOSED)->count(),
        ];

        return view('admin.lowongan.index', compact('jobs', 'stats'));
    }

    /**
     * Form tambah lowongan kerja baru.
     * GET /admin/lowongan/create — route name: admin.lowongan.create
     */
    public function create()
    {
        return view('admin.lowongan.create');
    }

    /**
     * Simpan lowongan kerja baru.
     * POST /admin/lowongan — route name: admin.lowongan.store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:150'],
            'category'        => ['required', 'string', 'max:100'],
            'employment_type' => ['required', Rule::in([
                JobListing::TYPE_FULL_TIME,
                JobListing::TYPE_PART_TIME,
                JobListing::TYPE_INTERNSHIP,
                JobListing::TYPE_CONTRACT,
            ])],
            'location'        => ['required', 'string', 'max:150'],
            'salary_min'      => ['nullable', 'numeric', 'min:0'],
            'salary_max'      => ['nullable', 'numeric', 'gte:salary_min'],
            'deadline'        => ['required', 'date', 'after:today'],
            'description'     => ['required', 'string'],
            'requirements'    => ['required', 'string'],
            'status'          => ['required', Rule::in([
                JobListing::STATUS_DRAFT,
                JobListing::STATUS_ACTIVE,
                JobListing::STATUS_CLOSED,
            ])],
        ], [
            'title.required'           => 'Judul lowongan wajib diisi.',
            'category.required'        => 'Kategori posisi wajib diisi.',
            'employment_type.required' => 'Tipe pekerjaan wajib dipilih.',
            'location.required'        => 'Lokasi kerja wajib diisi.',
            'deadline.required'        => 'Batas akhir lamaran wajib diisi.',
            'deadline.after'           => 'Batas akhir lamaran harus berupa tanggal setelah hari ini.',
            'description.required'     => 'Deskripsi pekerjaan wajib diisi.',
            'requirements.required'    => 'Kualifikasi pekerjaan wajib diisi.',
            'salary_max.gte'           => 'Gaji maksimal harus lebih besar atau sama dengan gaji minimal.',
        ]);

        // Generate slug unik
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (JobListing::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        $job = JobListing::create($validated);

        ActivityLog::record('create', 'Lowongan Kerja', "Admin membuat lowongan baru: '{$job->title}' ({$job->category}).");

        return redirect()
            ->route('admin.lowongan.index')
            ->with('success', 'Lowongan "' . $job->title . '" berhasil dibuat.');
    }

    /**
     * Detail lowongan kerja dan daftar pelamar terkait.
     * GET /admin/lowongan/{jobListing} — route name: admin.lowongan.show
     */
    public function show(Request $request, JobListing $jobListing)
    {
        $jobListing->loadCount('applications');
        $query = $jobListing->applications()->with('user')->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'withdrawn') {
                $query->whereNotNull('withdrawn_at');
            } elseif ($status === 'processing') {
                $query->whereNull('withdrawn_at')->whereIn('status', ['processing', 'interview']);
            } else {
                $query->whereNull('withdrawn_at')->where('status', $status);
            }
        }

        $applications = $query->paginate(10)->withQueryString();

        return view('admin.lowongan.show', compact('jobListing', 'applications'));
    }

    /**
     * Form edit lowongan kerja.
     * GET /admin/lowongan/{jobListing}/edit — route name: admin.lowongan.edit
     */
    public function edit(JobListing $jobListing)
    {
        return view('admin.lowongan.edit', compact('jobListing'));
    }

    /**
     * Update lowongan kerja.
     * PUT /admin/lowongan/{jobListing} — route name: admin.lowongan.update
     */
    public function update(Request $request, JobListing $jobListing)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:150'],
            'category'        => ['required', 'string', 'max:100'],
            'employment_type' => ['required', Rule::in([
                JobListing::TYPE_FULL_TIME,
                JobListing::TYPE_PART_TIME,
                JobListing::TYPE_INTERNSHIP,
                JobListing::TYPE_CONTRACT,
            ])],
            'location'        => ['required', 'string', 'max:150'],
            'salary_min'      => ['nullable', 'numeric', 'min:0'],
            'salary_max'      => ['nullable', 'numeric', 'gte:salary_min'],
            'deadline'        => ['required', 'date'],
            'description'     => ['required', 'string'],
            'requirements'    => ['required', 'string'],
            'status'          => ['required', Rule::in([
                JobListing::STATUS_DRAFT,
                JobListing::STATUS_ACTIVE,
                JobListing::STATUS_CLOSED,
            ])],
        ], [
            'title.required'           => 'Judul lowongan wajib diisi.',
            'category.required'        => 'Kategori posisi wajib diisi.',
            'employment_type.required' => 'Tipe pekerjaan wajib dipilih.',
            'location.required'        => 'Lokasi kerja wajib diisi.',
            'deadline.required'        => 'Batas akhir lamaran wajib diisi.',
            'description.required'     => 'Deskripsi pekerjaan wajib diisi.',
            'requirements.required'    => 'Kualifikasi pekerjaan wajib diisi.',
            'salary_max.gte'           => 'Gaji maksimal harus lebih besar atau sama dengan gaji minimal.',
        ]);

        // Update slug jika judul berubah
        if ($jobListing->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (JobListing::where('slug', $slug)->where('id', '!=', $jobListing->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $jobListing->update($validated);

        ActivityLog::record('update', 'Lowongan Kerja', "Admin memperbarui data lowongan: '{$jobListing->title}'.");

        return redirect()
            ->route('admin.lowongan.index')
            ->with('success', 'Lowongan "' . $jobListing->title . '" berhasil diperbarui.');
    }

    /**
     * Hapus lowongan kerja.
     * DELETE /admin/lowongan/{jobListing} — route name: admin.lowongan.destroy
     */
    public function destroy(JobListing $jobListing)
    {
        $appCount = $jobListing->applications()->count();

        if ($appCount > 0) {
            // Jika ada lamaran, ubah status jadi closed untuk menjaga integritas data
            $jobListing->update(['status' => JobListing::STATUS_CLOSED]);
            return back()->with('info', "Lowongan memiliki {$appCount} data lamaran, sehingga statusnya diubah menjadi 'Ditutup' demi menjaga riwayat data.");
        }

        $title = $jobListing->title;
        $jobListing->delete();

        ActivityLog::record('delete', 'Lowongan Kerja', "Admin menghapus lowongan: '{$title}'.");

        return back()->with('success', "Lowongan '{$title}' berhasil dihapus.");
    }

    /**
     * Ubah status lowongan secara cepat.
     * PATCH /admin/lowongan/{jobListing}/toggle-status — route name: admin.lowongan.toggle
     */
    public function toggleStatus(Request $request, JobListing $jobListing)
    {
        $newStatus = match ($jobListing->status) {
            JobListing::STATUS_ACTIVE => JobListing::STATUS_CLOSED,
            default                   => JobListing::STATUS_ACTIVE,
        };

        $jobListing->update(['status' => $newStatus]);

        ActivityLog::record('status_change', 'Lowongan Kerja', "Admin mengubah status lowongan '{$jobListing->title}' menjadi '{$jobListing->status_label}'.");

        return back()->with('success', "Status lowongan '{$jobListing->title}' diubah menjadi {$jobListing->status_label}.");
    }
}
