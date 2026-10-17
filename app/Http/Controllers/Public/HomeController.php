<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Job;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Jika sudah login, arahkan langsung ke dashboard sesuai role
        if (auth()->check()) {
            return auth()->user()->isAdmin()
                ? redirect()->route('company.dashboard')
                : redirect()->route('job-seeker.dashboard');
        }

        // Ambil profil perusahaan (singleton, auto-create kalau belum ada)
        $company = Company::current();

        // Ambil 3 lowongan aktif terbaru untuk ditonjolkan di beranda
        $featuredJobs = Job::publiclyVisible()
            ->latest()
            ->take(3)
            ->get();

        // Statistik ringkas untuk hero section (opsional)
        $stats = [
            'active_jobs' => Job::publiclyVisible()->count(),
        ];

        return view('home', compact('company', 'featuredJobs', 'stats'));
    }
}
