<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    /**
     * Id perusahaan milik admin yang sedang login (BR-08).
     * Admin hanya boleh mengelola lowongan milik perusahaannya.
     */
    private function companyId(Request $request): int
    {
        return $request->user()->company?->id ?? Company::current()->id;
    }

    /**
     * Tampilkan daftar lowongan kerja di panel admin.
     * GET /company/jobs — route name: company.jobs.index
     */
    public function index(Request $request)
    {
        $companyId = $this->companyId($request);

        $query = Job::where('company_id', $companyId)->withCount('applications')->latest();

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
            'total'  => Job::where('company_id', $companyId)->count(),
            'active' => Job::where('company_id', $companyId)->where('status', Job::STATUS_ACTIVE)->count(),
            'draft'  => Job::where('company_id', $companyId)->where('status', Job::STATUS_DRAFT)->count(),
            'closed' => Job::where('company_id', $companyId)->where('status', Job::STATUS_CLOSED)->count(),
        ];

        return view('company.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Form tambah lowongan kerja baru.
     * GET /company/jobs/create — route name: company.jobs.create
     */
    public function create()
    {
        return view('company.jobs.create');
    }

    /**
     * Simpan lowongan kerja baru.
     * POST /company/jobs — route name: company.jobs.store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:150'],
            'category'        => ['required', 'string', 'max:100'],
            'employment_type' => ['required', Rule::in([
                Job::TYPE_FULL_TIME,
                Job::TYPE_PART_TIME,
                Job::TYPE_INTERNSHIP,
                Job::TYPE_CONTRACT,
            ])],
            'location'        => ['required', 'string', 'max:150'],
            'salary_min'      => ['nullable', 'numeric', 'min:0', 'max:' . Job::SALARY_MAX_ALLOWED],
            'salary_max'      => ['nullable', 'numeric', 'gte:salary_min', 'max:' . Job::SALARY_MAX_ALLOWED],
            'deadline'        => ['required', 'date', 'after:today'],
            'description'     => ['required', 'string'],
            'requirements'    => ['required', 'string'],
            'status'          => ['required', Rule::in([
                Job::STATUS_DRAFT,
                Job::STATUS_ACTIVE,
                Job::STATUS_CLOSED,
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
            'salary_min.max'           => 'Gaji minimal terlalu besar. Nilai maksimal yang dapat disimpan adalah 9.999.999.999 (10 digit).',
            'salary_max.max'           => 'Gaji maksimal terlalu besar. Nilai maksimal yang dapat disimpan adalah 9.999.999.999 (10 digit).',
        ]);

        // Generate slug unik
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Job::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // BR-08 / §10.2: company_id selalu dari akun admin yang login.
        $validated['company_id'] = $this->companyId($request);

        $job = Job::create($validated);

        ActivityLog::record('create', 'Lowongan Kerja', "Admin membuat lowongan baru: '{$job->title}' ({$job->category}).");

        return redirect()
            ->route('company.jobs.index')
            ->with('success', 'Lowongan "' . $job->title . '" berhasil dibuat.');
    }

    /**
     * Detail lowongan kerja dan daftar pelamar terkait.
     * GET /company/jobs/{job} — route name: company.jobs.show
     */
    public function show(Request $request, Job $job)
    {
        abort_unless($job->company_id === $this->companyId($request), 403);

        $job->loadCount('applications');
        $query = $job->applications()->with('jobSeeker')->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'ILIKE', "%{$search}%")
                  ->orWhere('applicant_email', 'ILIKE', "%{$search}%")
                  ->orWhere('applicant_phone', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'withdrawn') {
                $query->whereNotNull('withdrawn_at');
            } elseif ($status === 'processing') {
                $query->whereNull('withdrawn_at')->where('status', 'processing');
            } else {
                $query->whereNull('withdrawn_at')->where('status', $status);
            }
        }

        $applications = $query->paginate(10)->withQueryString();

        return view('company.jobs.show', compact('job', 'applications'));
    }

    /**
     * Form edit lowongan kerja.
     * GET /company/jobs/{job}/edit — route name: company.jobs.edit
     */
    public function edit(Request $request, Job $job)
    {
        abort_unless($job->company_id === $this->companyId($request), 403);

        return view('company.jobs.edit', compact('job'));
    }

    /**
     * Update lowongan kerja.
     * PUT /company/jobs/{job} — route name: company.jobs.update
     */
    public function update(Request $request, Job $job)
    {
        abort_unless($job->company_id === $this->companyId($request), 403);

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:150'],
            'category'        => ['required', 'string', 'max:100'],
            'employment_type' => ['required', Rule::in([
                Job::TYPE_FULL_TIME,
                Job::TYPE_PART_TIME,
                Job::TYPE_INTERNSHIP,
                Job::TYPE_CONTRACT,
            ])],
            'location'        => ['required', 'string', 'max:150'],
            'salary_min'      => ['nullable', 'numeric', 'min:0', 'max:' . Job::SALARY_MAX_ALLOWED],
            'salary_max'      => ['nullable', 'numeric', 'gte:salary_min', 'max:' . Job::SALARY_MAX_ALLOWED],
            // Deadline boleh tetap bernilai lama (mis. lowongan lama yang tinggal diedit
            // bagian lain). Bila diubah, nilainya wajib setelah hari ini.
            'deadline'        => [
                'required',
                'date',
                function (string $attribute, mixed $value, \Closure $fail) use ($job) {
                    if ($value === $job->deadline->toDateString()) {
                        return;
                    }

                    if (! \Illuminate\Support\Carbon::parse($value)->startOfDay()->isAfter(now()->startOfDay())) {
                        $fail('Batas akhir lamaran yang baru harus berupa tanggal setelah hari ini.');
                    }
                },
            ],
            'description'     => ['required', 'string'],
            'requirements'    => ['required', 'string'],
            'status'          => ['required', Rule::in([
                Job::STATUS_DRAFT,
                Job::STATUS_ACTIVE,
                Job::STATUS_CLOSED,
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
            'salary_min.max'           => 'Gaji minimal terlalu besar. Nilai maksimal yang dapat disimpan adalah 9.999.999.999 (10 digit).',
            'salary_max.max'           => 'Gaji maksimal terlalu besar. Nilai maksimal yang dapat disimpan adalah 9.999.999.999 (10 digit).',
        ]);

        // Update slug jika judul berubah
        if ($job->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Job::where('slug', $slug)->where('id', '!=', $job->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $job->update($validated);

        ActivityLog::record('update', 'Lowongan Kerja', "Admin memperbarui data lowongan: '{$job->title}'.");

        return redirect()
            ->route('company.jobs.index')
            ->with('success', 'Lowongan "' . $job->title . '" berhasil diperbarui.');
    }

    /**
     * Hapus lowongan kerja.
     * DELETE /company/jobs/{job} — route name: company.jobs.destroy
     */
    public function destroy(Request $request, Job $job)
    {
        abort_unless($job->company_id === $this->companyId($request), 403);

        $appCount = $job->applications()->count();

        if ($appCount > 0) {
            // Jika ada lamaran, ubah status jadi closed untuk menjaga integritas data
            $job->update(['status' => Job::STATUS_CLOSED]);
            return back()->with('info', "Lowongan memiliki {$appCount} data lamaran, sehingga statusnya diubah menjadi 'Ditutup' demi menjaga riwayat data.");
        }

        $title = $job->title;
        $job->delete();

        ActivityLog::record('delete', 'Lowongan Kerja', "Admin menghapus lowongan: '{$title}'.");

        return back()->with('success', "Lowongan '{$title}' berhasil dihapus.");
    }

    /**
     * Ubah status lowongan secara cepat.
     * PATCH /company/jobs/{job}/toggle-status — route name: company.jobs.toggle
     */
    public function toggleStatus(Request $request, Job $job)
    {
        abort_unless($job->company_id === $this->companyId($request), 403);

        $newStatus = match ($job->status) {
            Job::STATUS_ACTIVE => Job::STATUS_CLOSED,
            default                   => Job::STATUS_ACTIVE,
        };

        $job->update(['status' => $newStatus]);

        ActivityLog::record('status_change', 'Lowongan Kerja', "Admin mengubah status lowongan '{$job->title}' menjadi '{$job->status_label}'.");

        return back()->with('success', "Status lowongan '{$job->title}' diubah menjadi {$job->status_label}.");
    }
}
