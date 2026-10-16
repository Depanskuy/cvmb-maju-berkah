<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan dashboard admin perusahaan.
     * GET /admin/dashboard — route name: admin.dashboard
     */
    public function index()
    {
        $stats = [
            'total_jobs'        => JobListing::count(),
            'active_jobs'       => JobListing::where('status', JobListing::STATUS_ACTIVE)->count(),
            'total_apps'        => Application::count(),
            'pending_apps'      => Application::whereNull('withdrawn_at')->where('status', Application::STATUS_PENDING)->count(),
            'total_seekers'     => User::where('role', User::ROLE_SEEKER)->count(),
        ];

        $recentApplications = Application::with('jobListing')
            ->latest()
            ->take(6)
            ->get();

        $activeJobListings = JobListing::withCount('applications')
            ->where('status', JobListing::STATUS_ACTIVE)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentApplications', 'activeJobListings'));
    }
}
