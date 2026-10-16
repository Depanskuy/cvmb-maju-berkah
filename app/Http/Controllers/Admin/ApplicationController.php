<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    /**
     * Tampilkan daftar seluruh lamaran masuk di panel admin.
     * GET /admin/lamaran — route name: admin.lamaran.index
     */
    public function index(Request $request)
    {
        $query = Application::with(['jobListing', 'user'])->latest();

        // Filter lowongan
        if ($request->filled('job_id')) {
            $query->where('job_listing_id', $request->input('job_id'));
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
        $jobListings = JobListing::orderBy('title')->get();

        $stats = [
            'total'      => Application::count(),
            'pending'    => Application::whereNull('withdrawn_at')->where('status', Application::STATUS_PENDING)->count(),
            'processing' => Application::whereNull('withdrawn_at')->whereIn('status', [Application::STATUS_PROCESSING, 'interview'])->count(),
            'accepted'   => Application::whereNull('withdrawn_at')->where('status', Application::STATUS_ACCEPTED)->count(),
            'rejected'   => Application::whereNull('withdrawn_at')->where('status', Application::STATUS_REJECTED)->count(),
            'withdrawn'  => Application::whereNotNull('withdrawn_at')->count(),
        ];

        return view('admin.lamaran.index', compact('applications', 'jobListings', 'stats'));
    }

    /**
     * Tampilkan detail lamaran tertentu.
     * GET /admin/lamaran/{application} — route name: admin.lamaran.show
     */
    public function show(Application $application)
    {
        $application->load(['jobListing', 'user']);

        return view('admin.lamaran.show', compact('application'));
    }

    /**
     * Perbarui status dan catatan review lamaran.
     * PATCH /admin/lamaran/{application}/status — route name: admin.lamaran.status.update
     */
    public function updateStatus(Request $request, Application $application)
    {
        if ($application->isWithdrawn()) {
            return back()->with('error', 'Lamaran ini telah dibatalkan oleh pelamar dan tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Application::STATUS_PENDING,
                Application::STATUS_PROCESSING,
                'interview',
                Application::STATUS_ACCEPTED,
                Application::STATUS_REJECTED,
            ])],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ], [
            'status.required' => 'Status lamaran wajib dipilih.',
        ]);

        $application->update([
            'status'      => $validated['status'],
            'review_note' => $validated['review_note'] ?? null,
        ]);

        ActivityLog::record(
            'status_change',
            'Lamaran Masuk',
            "Admin memperbarui status lamaran {$application->applicant_name} untuk posisi {$application->jobListing->title} menjadi '{$application->status_label}'."
        );

        return back()->with('success', "Status lamaran {$application->applicant_name} berhasil diperbarui menjadi {$application->status_label}.");
    }

    /**
     * Unduh berkas CV pelamar.
     * GET /admin/lamaran/{application}/cv — route name: admin.lamaran.cv
     */
    public function downloadCv(Application $application): StreamedResponse
    {
        if (! $application->cv_path || ! Storage::disk('local')->exists($application->cv_path)) {
            abort(404, 'Berkas CV tidak ditemukan di penyimpanan server.');
        }

        $filename = 'CV_' . str($application->applicant_name)->slug('_') . '.pdf';

        return Storage::disk('local')->download($application->cv_path, $filename);
    }
}
