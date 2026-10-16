<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Models\JobListing;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Ambil profil perusahaan (singleton, auto-create kalau belum ada)
        $company = CompanyProfile::current();

        // Ambil 3 lowongan aktif terbaru untuk ditonjolkan di beranda
        $featuredJobs = JobListing::publiclyVisible()
            ->latest()
            ->take(3)
            ->get();

        // Statistik ringkas untuk hero section (opsional)
        $stats = [
            'active_jobs' => JobListing::publiclyVisible()->count(),
        ];

        return view('home', compact('company', 'featuredJobs', 'stats'));
    }
}
