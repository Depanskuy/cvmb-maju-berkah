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
use App\Http\Controllers\Admin\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Admin\JobController as CompanyJobController;
use App\Http\Controllers\Admin\ApplicationController as CompanyApplicationController;
use App\Http\Controllers\Admin\SettingController as CompanyProfileController;
use App\Http\Controllers\Admin\PartnershipController as CompanyPartnershipController;
use App\Http\Controllers\Admin\FeedbackController as CompanyFeedbackController;
use App\Http\Controllers\Admin\ProjectController as CompanyProjectController;
use App\Http\Controllers\Admin\ActivityLogController as CompanyActivityLogController;

/*
|--------------------------------------------------------------------------
| Public Routes (§9.1)
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/about',    fn() => view('about'))->name('about');
Route::get('/services', fn() => view('services'))->name('services');
Route::get('/contact',  fn() => view('contact'))->name('contact');
Route::post('/contact/partnership', [CompanyPartnershipController::class, 'storePublic'])
    ->middleware('throttle:10,1')
    ->name('contact.partnership');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{job}', [CareerController::class, 'show'])->name('careers.show');

// Alur Lamar Pekerjaan (wajib login sebagai pencari kerja)
Route::get('/careers/{job}/apply', [CareerController::class, 'apply'])->name('careers.apply.create');
Route::post('/careers/{job}/apply', [CareerController::class, 'storeApplication'])
    ->middleware(['auth', 'role:job_seeker'])
    ->name('careers.apply');

/*
|--------------------------------------------------------------------------
| Guest Routes (Hanya Pengunjung Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');

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
| Role: Job Seeker — Pencari Kerja (§9.3)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:job_seeker'])->prefix('job-seeker')->name('job-seeker.')->group(function () {
    // Dashboard Pelamar
    Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('dashboard');

    // Menu "Lamaran Saya"
    Route::get('/applications', [SeekerApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{jobApplication}', [SeekerApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{jobApplication}/edit', [SeekerApplicationController::class, 'edit'])->name('applications.edit');
    Route::patch('/applications/{jobApplication}', [SeekerApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{jobApplication}', [SeekerApplicationController::class, 'withdraw'])->name('applications.destroy');
    Route::get('/applications/{jobApplication}/cv', [SeekerApplicationController::class, 'downloadCv'])->name('applications.cv');

    // Jelajahi Lowongan (tambahan di luar blueprint §9.3)
    Route::get('/jobs', [SeekerJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [SeekerJobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/apply', [SeekerJobController::class, 'apply'])->name('jobs.apply');

    // Profil Pencari Kerja (§9.3 + CRUD tambahan)
    Route::get('/profile', [SeekerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [SeekerProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [SeekerProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [SeekerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [SeekerProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [SeekerProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/cv', [SeekerProfileController::class, 'downloadCv'])->name('profile.cv');

    // Kirim Feedback Pengalaman (tambahan)
    Route::post('/feedback', [CompanyFeedbackController::class, 'storeSeeker'])->name('feedback.store');
});

/*
|--------------------------------------------------------------------------
| Role: Company Admin — Admin Perusahaan (§9.2)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:company_admin'])->prefix('company')->name('company.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

    // Profil Perusahaan
    Route::get('/profile', [CompanyProfileController::class, 'index'])->name('profile.edit');
    Route::put('/profile', [CompanyProfileController::class, 'update'])->name('profile.update');

    // CRUD Lowongan Kerja
    Route::get('/jobs', [CompanyJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [CompanyJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [CompanyJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [CompanyJobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [CompanyJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [CompanyJobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [CompanyJobController::class, 'destroy'])->name('jobs.destroy');
    Route::patch('/jobs/{job}/toggle-status', [CompanyJobController::class, 'toggleStatus'])->name('jobs.toggle');

    // Pengelolaan Lamaran Masuk
    Route::get('/applications', [CompanyApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{jobApplication}', [CompanyApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{jobApplication}/status', [CompanyApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::get('/applications/{jobApplication}/cv', [CompanyApplicationController::class, 'downloadCv'])->name('applications.cv');

    // Pengajuan Kerja Sama (tambahan di luar blueprint)
    Route::get('/partnership', [CompanyPartnershipController::class, 'index'])->name('partnership.index');
    Route::patch('/partnership/{partnership}/status', [CompanyPartnershipController::class, 'updateStatus'])->name('partnership.status.update');

    // Feedback Layanan (tambahan)
    Route::get('/feedback', [CompanyFeedbackController::class, 'index'])->name('feedback.index');
    Route::patch('/feedback/{feedback}/status', [CompanyFeedbackController::class, 'updateStatus'])->name('feedback.status.update');
    Route::delete('/feedback/{feedback}', [CompanyFeedbackController::class, 'destroy'])->name('feedback.destroy');

    // Project & Portofolio (tambahan)
    Route::get('/project', [CompanyProjectController::class, 'index'])->name('project.index');
    Route::post('/project', [CompanyProjectController::class, 'store'])->name('project.store');
    Route::put('/project/{project}', [CompanyProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{project}', [CompanyProjectController::class, 'destroy'])->name('project.destroy');

    // Log Aktivitas & Audit Trail (tambahan)
    Route::get('/activity-log', [CompanyActivityLogController::class, 'index'])->name('activity-log.index');
});
