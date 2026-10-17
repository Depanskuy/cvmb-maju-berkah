<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Proses logout pengguna.
     * POST /logout — route name: logout
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record(
                'auth',
                'Autentikasi',
                "Pengguna {$user->name} ({$user->role_label}) logout dari sistem.",
                $user
            );
        }

        // 1. Logout pengguna
        Auth::logout();
        // 2. Batalkan session yang ada
        $request->session()->invalidate();
        // 3. Perbarui token CSRF
        $request->session()->regenerateToken();
        // 4. Kembali ke beranda
        return redirect()->route('home')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }
}
