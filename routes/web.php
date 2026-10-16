<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CareerController;

Route::get('/', HomeController::class)->name('home');
Route::get('/about',    fn() => view('about'))->name('about');
Route::get('/services', fn() => view('services'))->name('services');
Route::get('/contact',  fn() => view('contact'))->name('contact');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobListing}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{jobListing}/apply', [CareerController::class, 'storeApplication'])
    ->name('careers.apply.store');

// Guest — hanya yang belum login bisa akses
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Logout — hanya yang sudah login
Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');
