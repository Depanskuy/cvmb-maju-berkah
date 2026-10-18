<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Tampilkan katalog lowongan kerja di dalam portal pencari kerja.
     * GET /seeker/jobs — route name: job-seeker.jobs.index
     */
    public function index(Request $request)
    {
        $query = Job::publiclyVisible();

        // Filter keyword
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ILIKE', "%{$keyword}%")
                  ->orWhere('category', 'ILIKE', "%{$keyword}%")
                  ->orWhere('location', 'ILIKE', "%{$keyword}%");
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter tipe pekerjaan
        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        // Filter lokasi
        if ($request->filled('location')) {
            $query->where('location', 'ILIKE', '%' . $request->input('location') . '%');
        }

        // Urutan hasil
        match ($request->input('sort', 'latest')) {
            'deadline' => $query->orderBy('deadline'),
            'title'    => $query->orderBy('title'),
            default    => $query->latest(),
        };

        $jobs = $query->paginate(9)->withQueryString();

        $categories = Job::publiclyVisible()
            ->select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $appliedJobIds = auth()->user()->applications()
            ->whereNull('withdrawn_at')
            ->pluck('job_id')
            ->toArray();

        return view('job-seeker.jobs.index', compact('jobs', 'categories', 'appliedJobIds'));
    }

    /**
     * Tampilkan rincian lowongan kerja di dalam portal pencari kerja.
     * GET /seeker/jobs/{job} — route name: job-seeker.jobs.show
     */
    public function show(Job $job)
    {
        abort_unless(
            $job->status === 'active' || $job->status === 'closed',
            404
        );

        $hasApplied = auth()->user()->applications()
            ->where('job_id', $job->id)
            ->whereNull('withdrawn_at')
            ->first();

        return view('job-seeker.jobs.show', compact('job', 'hasApplied'));
    }

    /**
     * Form lamar lowongan kerja di dalam portal pencari kerja.
     * GET /seeker/jobs/{job}/apply — route name: job-seeker.jobs.apply
     */
    public function apply(Job $job)
    {
        abort_unless($job->canAcceptApplications(), 403, 'Lowongan ini sudah tidak menerima lamaran.');

        $existing = auth()->user()->applications()
            ->where('job_id', $job->id)
            ->whereNull('withdrawn_at')
            ->first();

        if ($existing) {
            return redirect()
                ->route('job-seeker.applications.show', $existing)
                ->with('info', 'Anda sudah pernah mengajukan lamaran untuk posisi ini.');
        }

        $user = auth()->user();
        $profile = $user->profile();

        return view('job-seeker.jobs.apply', compact('job', 'user', 'profile'));
    }
}
