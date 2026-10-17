<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan dashboard admin perusahaan.
     * GET /company/dashboard — route name: company.dashboard
     */
    public function index(Request $request)
    {
        // BR-08: ringkasan hanya untuk perusahaan milik admin yang login.
        $companyId = $request->user()->company?->id ?? Company::current()->id;

        $apps = fn () => JobApplication::query()
            ->whereHas('job', fn ($q) => $q->where('company_id', $companyId));

        $stats = [
            'total_jobs'        => Job::where('company_id', $companyId)->count(),
            'active_jobs'       => Job::where('company_id', $companyId)->where('status', Job::STATUS_ACTIVE)->count(),
            'total_apps'        => $apps()->count(),
            'pending_apps'      => $apps()->whereNull('withdrawn_at')->where('status', JobApplication::STATUS_PENDING)->count(),
            'total_seekers'     => User::where('role', User::ROLE_SEEKER)->count(),
        ];

        $recentApplications = $apps()->with('job')
            ->latest()
            ->take(6)
            ->get();

        $activeJobs = Job::where('company_id', $companyId)->withCount('applications')
            ->where('status', Job::STATUS_ACTIVE)
            ->latest()
            ->take(5)
            ->get();

        return view('company.dashboard', compact('stats', 'recentApplications', 'activeJobs'));
    }
}
