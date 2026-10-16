<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\ActivityLog::record(
                'auth',
                'Autentikasi',
                "Pengguna {$user->name} ({$user->role_label}) logout dari sistem.",
                $user
            );
        }

        //1.Logout the user
        Auth::logout(); 
        //2.Invalidate the existing session
        $request->session()->invalidate(); 
        //3.Regenerate the CSRF token
        $request->session()->regenerateToken(); 
        //4.Redirect to the homepage or login page
        return redirect()->route('home')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
