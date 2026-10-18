<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     * GET /login — route name: login
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi pengguna.
     * POST /login — route name: login.store
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba autentikasi
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau kata sandi yang Anda masukkan salah.',
                ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        ActivityLog::record(
            'auth',
            'Autentikasi',
            "Pengguna {$user->name} ({$user->role_label}) berhasil login ke sistem.",
            $user
        );

        // 3. Redirect sesuai role
        if ($user->isAdmin()) {
            return redirect()->intended(route('company.dashboard'));
        }

        return redirect()->intended(route('job-seeker.dashboard'));
    }
}
