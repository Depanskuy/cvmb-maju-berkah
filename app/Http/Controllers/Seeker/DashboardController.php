<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama pencari kerja (Pelamar).
     * GET /seeker/dashboard — route name: seeker.dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total'      => Application::where('user_id', $user->id)->count(),
            'pending'    => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->where('status', Application::STATUS_PENDING)->count(),
            'processing' => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->whereIn('status', [Application::STATUS_PROCESSING, 'interview'])->count(),
            'accepted'   => Application::where('user_id', $user->id)->whereNull('withdrawn_at')->where('status', Application::STATUS_ACCEPTED)->count(),
        ];

        $recentApplications = Application::with('jobListing')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $profile = $user->jobSeekerProfile;

        $availableJobs = JobListing::where('status', JobListing::STATUS_ACTIVE)
            ->whereDate('deadline', '>=', now()->toDateString())
            ->latest()
            ->take(4)
            ->get();

        return view('seeker.dashboard', compact('stats', 'recentApplications', 'profile', 'availableJobs'));
    }
}
