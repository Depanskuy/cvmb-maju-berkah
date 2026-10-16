<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobSeekerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Tampilkan formulir registrasi pencari kerja.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun pencari kerja baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Alamat email aktif wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email ini sudah terdaftar. Silakan login.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'=> 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role'     => User::ROLE_SEEKER,
        ]);

        // Buat profil default
        JobSeekerProfile::create([
            'user_id' => $user->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        \App\Models\ActivityLog::record(
            'auth',
            'Autentikasi',
            "Pencari kerja baru terdaftar: {$user->name} ({$user->email}).",
            $user
        );

        return redirect()->intended(route('seeker.dashboard'))
            ->with('success', 'Selamat datang, ' . $user->name . '! Akun pencari kerja Anda berhasil dibuat.');
    }
}
