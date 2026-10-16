<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CareerController;
use App\Http\Controllers\Seeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\Seeker\ApplicationController as SeekerApplicationController;
use App\Http\Controllers\Seeker\ProfileController as SeekerProfileController;
use App\Http\Controllers\Seeker\JobController as SeekerJobController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobListingController as AdminJobListingController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\PartnershipController as AdminPartnershipController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/about',    fn() => view('about'))->name('about');
Route::get('/services', fn() => view('services'))->name('services');
Route::get('/contact',  fn() => view('contact'))->name('contact');
Route::post('/contact/partnership', [AdminPartnershipController::class, 'storePublic'])->name('contact.partnership');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobListing}', [CareerController::class, 'show'])->name('careers.show');

// Alur Lamar Pekerjaan (wajib login pencari kerja)
Route::get('/career/apply-job/{jobListing}', [CareerController::class, 'apply'])->name('careers.apply.create');
Route::post('/careers/{jobListing}/apply', [CareerController::class, 'storeApplication'])->name('careers.apply.store');

/*
|--------------------------------------------------------------------------
| Guest Routes (Hanya Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::post('/logout', LogoutController::class)->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Role: Job Seeker (Pencari Kerja)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:job_seeker'])->prefix('seeker')->name('seeker.')->group(function () {
    // Dashboard Pelamar
    Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('dashboard');

    // Menu "Lamaran Saya"
    Route::get('/applications', [SeekerApplicationController::class, 'index'])->name('applications');
    Route::get('/applications/{application}', [SeekerApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}/withdraw', [SeekerApplicationController::class, 'withdraw'])->name('applications.withdraw');
    Route::get('/applications/{application}/cv', [SeekerApplicationController::class, 'downloadCv'])->name('applications.cv');

    // Jelajahi Lowongan (Portal Pelamar)
    Route::get('/jobs', [SeekerJobController::class, 'index'])->name('jobs');
    Route::get('/jobs/{jobListing}', [SeekerJobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{jobListing}/apply', [SeekerJobController::class, 'apply'])->name('jobs.apply');

    // Profil Pencari Kerja (CRUD Lengkap)
    Route::get('/profile', [SeekerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [SeekerProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [SeekerProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [SeekerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [SeekerProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [SeekerProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/cv', [SeekerProfileController::class, 'downloadCv'])->name('profile.cv');

    // Kirim Feedback Pengalaman
    Route::post('/feedback', [AdminFeedbackController::class, 'storeSeeker'])->name('feedback.store');
});

/*
|--------------------------------------------------------------------------
| Role: Company Admin (Admin Perusahaan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:company_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Lowongan Kerja
    Route::get('/lowongan', [AdminJobListingController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/create', [AdminJobListingController::class, 'create'])->name('lowongan.create');
    Route::post('/lowongan', [AdminJobListingController::class, 'store'])->name('lowongan.store');
    Route::get('/lowongan/{jobListing}', [AdminJobListingController::class, 'show'])->name('lowongan.show');
    Route::get('/lowongan/{jobListing}/edit', [AdminJobListingController::class, 'edit'])->name('lowongan.edit');
    Route::put('/lowongan/{jobListing}', [AdminJobListingController::class, 'update'])->name('lowongan.update');
    Route::delete('/lowongan/{jobListing}', [AdminJobListingController::class, 'destroy'])->name('lowongan.destroy');
    Route::patch('/lowongan/{jobListing}/toggle-status', [AdminJobListingController::class, 'toggleStatus'])->name('lowongan.toggle');

    // Pengelolaan Lamaran Masuk
    Route::get('/lamaran', [AdminApplicationController::class, 'index'])->name('lamaran.index');
    Route::get('/lamaran/{application}', [AdminApplicationController::class, 'show'])->name('lamaran.show');
    Route::patch('/lamaran/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('lamaran.status.update');
    Route::get('/lamaran/{application}/cv', [AdminApplicationController::class, 'downloadCv'])->name('lamaran.cv');

    // Pengajuan Kerja Sama
    Route::get('/kerja-sama', [AdminPartnershipController::class, 'index'])->name('kerja-sama.index');
    Route::patch('/kerja-sama/{partnership}/status', [AdminPartnershipController::class, 'updateStatus'])->name('kerja-sama.status.update');

    // Feedback Layanan
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::patch('/feedback/{feedback}/status', [AdminFeedbackController::class, 'updateStatus'])->name('feedback.status.update');
    Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');

    // Project & Portofolio
    Route::get('/project', [AdminProjectController::class, 'index'])->name('project.index');
    Route::post('/project', [AdminProjectController::class, 'store'])->name('project.store');
    Route::put('/project/{project}', [AdminProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{project}', [AdminProjectController::class, 'destroy'])->name('project.destroy');

    // Log Aktivitas & Audit Trail
    Route::get('/log', [AdminActivityLogController::class, 'index'])->name('log.index');

    // Pengaturan
    Route::get('/pengaturan', [AdminSettingController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [AdminSettingController::class, 'update'])->name('pengaturan.update');
});
