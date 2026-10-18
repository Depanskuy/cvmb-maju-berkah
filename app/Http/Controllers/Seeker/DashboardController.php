<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama pencari kerja (Pelamar).
     * GET /seeker/dashboard — route name: job-seeker.dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total'      => JobApplication::where('job_seeker_id', $user->id)->count(),
            'pending'    => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PENDING)->count(),
            'processing' => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PROCESSING)->count(),
            'accepted'   => JobApplication::where('job_seeker_id', $user->id)->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_ACCEPTED)->count(),
        ];

        $recentApplications = JobApplication::with('job')
            ->where('job_seeker_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $profile = $user->jobSeekerProfile;

        $availableJobs = Job::where('status', Job::STATUS_ACTIVE)
            ->whereDate('deadline', '>=', now()->toDateString())
            ->latest()
            ->take(4)
            ->get();

        return view('job-seeker.dashboard', compact('stats', 'recentApplications', 'profile', 'availableJobs'));
    }
}
