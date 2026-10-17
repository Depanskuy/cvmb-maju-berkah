<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Tampilkan katalog lowongan kerja di dalam portal pencari kerja.
     * GET /seeker/jobs — route name: seeker.jobs
     */
    public function index(Request $request)
    {
        $query = JobListing::publiclyVisible();

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

        $categories = JobListing::publiclyVisible()
            ->select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $appliedJobIds = auth()->user()->applications()
            ->whereNull('withdrawn_at')
            ->pluck('job_listing_id')
            ->toArray();

        return view('seeker.jobs.index', compact('jobs', 'categories', 'appliedJobIds'));
    }

    /**
     * Tampilkan rincian lowongan kerja di dalam portal pencari kerja.
     * GET /seeker/jobs/{jobListing} — route name: seeker.jobs.show
     */
    public function show(JobListing $jobListing)
    {
        abort_unless(
            $jobListing->status === 'active' || $jobListing->status === 'closed',
            404
        );

        $hasApplied = auth()->user()->applications()
            ->where('job_listing_id', $jobListing->id)
            ->whereNull('withdrawn_at')
            ->first();

        return view('seeker.jobs.show', compact('jobListing', 'hasApplied'));
    }

    /**
     * Form lamar lowongan kerja di dalam portal pencari kerja.
     * GET /seeker/jobs/{jobListing}/apply — route name: seeker.jobs.apply
     */
    public function apply(JobListing $jobListing)
    {
        abort_unless($jobListing->canAcceptApplications(), 403, 'Lowongan ini sudah tidak menerima lamaran.');

        $existing = auth()->user()->applications()
            ->where('job_listing_id', $jobListing->id)
            ->whereNull('withdrawn_at')
            ->first();

        if ($existing) {
            return redirect()
                ->route('seeker.applications.show', $existing)
                ->with('info', 'Anda sudah pernah mengajukan lamaran untuk posisi ini.');
        }

        $user = auth()->user();
        $profile = $user->profile();

        return view('seeker.jobs.apply', compact('jobListing', 'user', 'profile'));
    }
}
