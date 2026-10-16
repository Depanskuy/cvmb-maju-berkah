<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem & profil perusahaan.
     * GET /admin/pengaturan — route name: admin.pengaturan.index
     */
    public function index()
    {
        $company = CompanyProfile::current();

        return view('admin.pengaturan.index', compact('company'));
    }

    /**
     * Simpan pembaruan pengaturan profil perusahaan.
     * PUT /admin/pengaturan — route name: admin.pengaturan.update
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'address'     => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required'  => 'Nama perusahaan wajib diisi.',
            'email.required' => 'Email resmi perusahaan wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $company = CompanyProfile::current();
        $company->update($validated);

        return back()->with('success', 'Pengaturan profil perusahaan berhasil disimpan.');
    }
}
