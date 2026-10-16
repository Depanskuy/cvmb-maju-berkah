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
        // Jika sudah login, arahkan langsung ke dashboard sesuai role
        if (auth()->check()) {
            return auth()->user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('seeker.dashboard');
        }

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
