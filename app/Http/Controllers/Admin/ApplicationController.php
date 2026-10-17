<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Company;
use App\Models\Job;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    /**
     * Id perusahaan milik admin yang login (BR-08).
     */
    private function companyId(Request $request): int
    {
        return $request->user()->company?->id ?? Company::current()->id;
    }

    /**
     * Query dasar lamaran milik perusahaan admin yang login (BR-08).
     */
    private function scopedQuery(Request $request)
    {
        $companyId = $this->companyId($request);

        return JobApplication::query()
            ->whereHas('job', fn ($q) => $q->where('company_id', $companyId));
    }

    /**
     * Tampilkan daftar seluruh lamaran masuk di panel admin.
     * GET /company/applications — route name: company.applications.index
     */
    public function index(Request $request)
    {
        $companyId = $this->companyId($request);
        $query = $this->scopedQuery($request)->with(['job', 'jobSeeker'])->latest();

        // Filter lowongan
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->input('job_id'));
        }

        // Filter status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'withdrawn') {
                $query->whereNotNull('withdrawn_at');
            } else {
                $query->whereNull('withdrawn_at')->where('status', $status);
            }
        }

        // Pencarian pelamar
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'ILIKE', "%{$search}%")
                  ->orWhere('applicant_email', 'ILIKE', "%{$search}%")
                  ->orWhere('applicant_phone', 'ILIKE', "%{$search}%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();
        $jobs = Job::where('company_id', $companyId)->orderBy('title')->get();

        $base = $this->scopedQuery($request);
        $stats = [
            'total'      => (clone $base)->count(),
            'pending'    => (clone $base)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PENDING)->count(),
            'processing' => (clone $base)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PROCESSING)->count(),
            'accepted'   => (clone $base)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_ACCEPTED)->count(),
            'rejected'   => (clone $base)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_REJECTED)->count(),
            'withdrawn'  => (clone $base)->whereNotNull('withdrawn_at')->count(),
        ];

        return view('company.applications.index', compact('applications', 'jobs', 'stats'));
    }

    /**
     * Tampilkan detail lamaran tertentu.
     * GET /company/applications/{jobApplication} — route name: company.applications.show
     */
    public function show(Request $request, JobApplication $jobApplication)
    {
        abort_unless($jobApplication->job?->company_id === $this->companyId($request), 403);

        $jobApplication->load(['job', 'jobSeeker']);

        return view('company.applications.show', compact('jobApplication'));
    }

    /**
     * Perbarui status dan catatan review lamaran.
     * PATCH /company/applications/{jobApplication}/status — route name: company.applications.status
     */
    public function updateStatus(Request $request, JobApplication $jobApplication)
    {
        abort_unless($jobApplication->job?->company_id === $this->companyId($request), 403);

        if ($jobApplication->isWithdrawn()) {
            return back()->with('error', 'Lamaran ini telah dibatalkan oleh pelamar dan tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                JobApplication::STATUS_PENDING,
                JobApplication::STATUS_PROCESSING,
                JobApplication::STATUS_ACCEPTED,
                JobApplication::STATUS_REJECTED,
            ])],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ], [
            'status.required' => 'Status lamaran wajib dipilih.',
        ]);

        $jobApplication->update([
            'status'      => $validated['status'],
            'review_note' => $validated['review_note'] ?? null,
        ]);

        ActivityLog::record(
            'status_change',
            'Lamaran Masuk',
            "Admin memperbarui status lamaran {$jobApplication->applicant_name} untuk posisi {$jobApplication->job->title} menjadi '{$jobApplication->status_label}'."
        );

        return back()->with('success', "Status lamaran {$jobApplication->applicant_name} berhasil diperbarui menjadi {$jobApplication->status_label}.");
    }

    /**
     * Unduh berkas CV pelamar.
     * GET /company/applications/{jobApplication}/cv — route name: company.applications.cv
     */
    public function downloadCv(Request $request, JobApplication $jobApplication): StreamedResponse
    {
        abort_unless($jobApplication->job?->company_id === $this->companyId($request), 403);

        if (! $jobApplication->cv_path || ! Storage::disk('local')->exists($jobApplication->cv_path)) {
            abort(404, 'Berkas CV tidak ditemukan di penyimpanan server.');
        }

        $filename = 'CV_' . str($jobApplication->applicant_name)->slug('_') . '.pdf';

        return Storage::disk('local')->download($jobApplication->cv_path, $filename);
    }
}
