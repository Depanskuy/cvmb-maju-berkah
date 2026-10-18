<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem & profil perusahaan.
     * GET /company/profile — route name: company.profile.edit
     */
    public function index()
    {
        $company = Company::current();

        return view('company.profile.index', compact('company'));
    }

    /**
     * Simpan pembaruan pengaturan profil perusahaan.
     * PUT /company/profile — route name: company.profile.update
     */
    public function update(Request $request)
    {
        $company = Company::current();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'slug'          => ['required', 'string', 'max:150', Rule::unique('companies', 'slug')->ignore($company->id)],
            'tagline'       => ['nullable', 'string', 'max:200'],
            'description'   => ['required', 'string'],
            'vision'        => ['nullable', 'string'],
            'mission'       => ['nullable', 'string'],
            'services'      => ['nullable', 'string'],
            'address'       => ['nullable', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'logo'          => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
        ], [
            'name.required'          => 'Nama perusahaan wajib diisi.',
            'slug.required'          => 'Slug perusahaan wajib diisi.',
            'slug.unique'            => 'Slug sudah digunakan oleh data lain.',
            'description.required'   => 'Deskripsi perusahaan wajib diisi.',
            'contact_email.required' => 'Email resmi perusahaan wajib diisi.',
            'contact_email.email'    => 'Format email tidak valid.',
            'logo.mimes'             => 'Logo harus berformat PNG, JPG, WEBP, atau SVG.',
            'logo.max'               => 'Ukuran logo maksimal 2 MB.',
        ]);

        $data = $validated;
        unset($data['logo']);

        if ($request->hasFile('logo')) {
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        ActivityLog::record('update', 'Profil Perusahaan', "Admin memperbarui profil perusahaan '{$company->name}'.");

        return back()->with('success', 'Profil perusahaan berhasil disimpan.');
    }
}
