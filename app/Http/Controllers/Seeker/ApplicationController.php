<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    /**
     * Tampilkan daftar riwayat lamaran milik pencari kerja yang sedang login.
     * GET /seeker/applications — route name: job-seeker.applications.index
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = JobApplication::with('job')
            ->where('job_seeker_id', $user->id)
            ->latest();

        // Filter status jika ada
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'withdrawn') {
                $query->whereNotNull('withdrawn_at');
            } elseif ($status === 'processing') {
                $query->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PROCESSING);
            } else {
                $query->whereNull('withdrawn_at')->where('status', $status);
            }
        }

        // Filter search jika ada
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('job', function ($sub) use ($search) {
                    $sub->where('title', 'ILIKE', "%{$search}%")
                        ->orWhere('category', 'ILIKE', "%{$search}%")
                        ->orWhere('location', 'ILIKE', "%{$search}%");
                })->orWhere('applicant_name', 'ILIKE', "%{$search}%")
                  ->orWhere('applicant_email', 'ILIKE', "%{$search}%");
            });
        }

        $applications = $query->paginate(10)->withQueryString();

        $stats = [
            'total'      => JobApplication::where('job_seeker_id', $user->id)->count(),
            'pending'    => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PENDING)->count(),
            'processing' => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PROCESSING)->count(),
            'accepted'   => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_ACCEPTED)->count(),
            'rejected'   => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_REJECTED)->count(),
        ];

        return view('job-seeker.applications.index', compact('applications', 'stats'));
    }

    /**
     * Tampilkan detail lamaran tertentu.
     * GET /seeker/applications/{application} — route name: job-seeker.applications.show
     */
    public function show(Request $request, JobApplication $jobApplication)
    {
        // Pastikan lamaran milik user yang sedang login
        abort_unless(
            $jobApplication->job_seeker_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke lamaran ini.'
        );

        $jobApplication->load('job');

        return view('job-seeker.applications.show', compact('jobApplication'));
    }

    /**
     * Tampilkan form ubah lamaran (hanya saat status masih pending & belum ditarik).
     * GET /seeker/applications/{application}/edit — route name: job-seeker.applications.edit
     */
    public function edit(Request $request, JobApplication $jobApplication)
    {
        abort_unless(
            $jobApplication->job_seeker_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke lamaran ini.'
        );

        if (! $jobApplication->canBeWithdrawn()) {
            return redirect()
                ->route('job-seeker.applications.show', $jobApplication)
                ->with('error', 'Lamaran hanya dapat diubah selama status masih Menunggu (pending).');
        }

        $jobApplication->load('job');

        return view('job-seeker.applications.edit', compact('jobApplication'));
    }

    /**
     * Simpan perubahan lamaran (hanya saat status masih pending & belum ditarik).
     * PUT /seeker/applications/{application} — route name: job-seeker.applications.update
     */
    public function update(Request $request, JobApplication $jobApplication)
    {
        abort_unless(
            $jobApplication->job_seeker_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke lamaran ini.'
        );

        if (! $jobApplication->canBeWithdrawn()) {
            return back()->with('error', 'Lamaran hanya dapat diubah selama status masih Menunggu (pending).');
        }

        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:2000'],
            'education'    => ['nullable', 'string', 'max:100'],
            'skills'       => ['nullable', 'string', 'max:1000'],
            'cv'           => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'cv.mimes' => 'Berkas CV harus berformat PDF.',
            'cv.max'   => 'Ukuran berkas CV maksimal 2MB.',
        ]);

        $data = [
            'cover_letter' => $validated['cover_letter'] ?? null,
            'education'    => $validated['education'] ?? null,
            'skills'       => $validated['skills'] ?? null,
        ];

        if ($request->hasFile('cv')) {
            // Hapus berkas CV lama agar tidak menumpuk di penyimpanan
            if ($jobApplication->cv_path && Storage::disk('local')->exists($jobApplication->cv_path)) {
                Storage::disk('local')->delete($jobApplication->cv_path);
            }
            $data['cv_path'] = $request->file('cv')->store('cvs', 'local');
        }

        $jobApplication->update($data);

        ActivityLog::record(
            'update',
            'Lamaran Masuk',
            "Pelamar {$jobApplication->applicant_name} memperbarui berkas lamaran untuk posisi {$jobApplication->job->title}.",
            $request->user()
        );

        return redirect()
            ->route('job-seeker.applications.show', $jobApplication)
            ->with('success', 'Lamaran Anda berhasil diperbarui.');
    }

    /**
     * Batalkan lamaran (hanya jika masih berstatus pending dan belum dibatalkan).
     * DELETE /seeker/applications/{application}/withdraw — route name: job-seeker.applications.destroy
     */
    public function withdraw(Request $request, JobApplication $jobApplication)
    {
        abort_unless(
            $jobApplication->job_seeker_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses untuk membatalkan lamaran ini.'
        );

        if (! $jobApplication->canBeWithdrawn()) {
            return back()->with('error', 'Lamaran ini sudah diproses atau tidak dapat dibatalkan.');
        }

        $jobApplication->update([
            'withdrawn_at' => now(),
        ]);

        ActivityLog::record(
            'withdraw',
            'Lamaran Masuk',
            "Pelamar {$jobApplication->applicant_name} membatalkan berkas lamaran untuk posisi {$jobApplication->job->title}.",
            $request->user()
        );

        return back()->with('success', 'Lamaran Anda berhasil dibatalkan.');
    }

    /**
     * Unduh file CV lamaran milik pengguna yang login.
     * GET /seeker/applications/{application}/cv — route name: job-seeker.applications.cv
     */
    public function downloadCv(Request $request, JobApplication $jobApplication): StreamedResponse
    {
        abort_unless(
            $jobApplication->job_seeker_id === $request->user()->id,
            403,
            'Anda tidak memiliki izin mengunduh berkas ini.'
        );

        if (! $jobApplication->cv_path || ! Storage::disk('local')->exists($jobApplication->cv_path)) {
            abort(404, 'Berkas CV tidak ditemukan.');
        }

        $filename = 'CV_' . str($jobApplication->applicant_name)->slug('_') . '.pdf';

        return Storage::disk('local')->download($jobApplication->cv_path, $filename);
    }
}
