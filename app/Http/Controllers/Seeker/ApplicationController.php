<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    /**
     * Tampilkan daftar riwayat lamaran milik pencari kerja yang sedang login.
     * GET /seeker/applications — route name: seeker.applications
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Application::with('jobListing')
            ->where('user_id', $user->id)
            ->latest();

        // Filter status jika ada
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'withdrawn') {
                $query->whereNotNull('withdrawn_at');
            } elseif ($status === 'processing') {
                $query->whereNull('withdrawn_at')->whereIn('status', [Application::STATUS_PROCESSING, 'interview']);
            } else {
                $query->whereNull('withdrawn_at')->where('status', $status);
            }
        }

        // Filter search jika ada
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('jobListing', function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                })->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(10)->withQueryString();

        $stats = [
            'total'      => Application::where('user_id', $user->id)->count(),
            'pending'    => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->where('status', Application::STATUS_PENDING)->count(),
            'processing' => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->whereIn('status', [Application::STATUS_PROCESSING, 'interview'])->count(),
            'accepted'   => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->where('status', Application::STATUS_ACCEPTED)->count(),
            'rejected'   => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->where('status', Application::STATUS_REJECTED)->count(),
        ];

        return view('seeker.applications.index', compact('applications', 'stats'));
    }

    /**
     * Tampilkan detail lamaran tertentu.
     * GET /seeker/applications/{application} — route name: seeker.applications.show
     */
    public function show(Request $request, Application $application)
    {
        // Pastikan lamaran milik user yang sedang login
        abort_unless(
            $application->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke lamaran ini.'
        );

        $application->load('jobListing');

        return view('seeker.applications.show', compact('application'));
    }

    /**
     * Batalkan lamaran (hanya jika masih berstatus pending dan belum dibatalkan).
     * DELETE /seeker/applications/{application}/withdraw — route name: seeker.applications.withdraw
     */
    public function withdraw(Request $request, Application $application)
    {
        abort_unless(
            $application->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses untuk membatalkan lamaran ini.'
        );

        if (! $application->canBeWithdrawn()) {
            return back()->with('error', 'Lamaran ini sudah diproses atau tidak dapat dibatalkan.');
        }

        $application->update([
            'withdrawn_at' => now(),
        ]);

        ActivityLog::record(
            'withdraw',
            'Lamaran Masuk',
            "Pelamar {$application->applicant_name} membatalkan berkas lamaran untuk posisi {$application->jobListing->title}.",
            $request->user()
        );

        return back()->with('success', 'Lamaran Anda berhasil dibatalkan.');
    }

    /**
     * Unduh file CV lamaran milik pengguna yang login.
     * GET /seeker/applications/{application}/cv — route name: seeker.applications.cv
     */
    public function downloadCv(Request $request, Application $application): StreamedResponse
    {
        abort_unless(
            $application->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki izin mengunduh berkas ini.'
        );

        if (! $application->cv_path || ! Storage::disk('local')->exists($application->cv_path)) {
            abort(404, 'Berkas CV tidak ditemukan.');
        }

        $filename = 'CV_' . str($application->applicant_name)->slug('_') . '.pdf';

        return Storage::disk('local')->download($application->cv_path, $filename);
    }
}
