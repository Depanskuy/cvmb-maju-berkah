<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobSeekerProfile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    /**
     * READ: Tampilkan detail profil pencari kerja.
     * GET /seeker/profile — route name: seeker.profile.show
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->jobSeekerProfile()->first();

        return view('seeker.profile.show', compact('user', 'profile'));
    }

    /**
     * CREATE: Form pembuatan profil baru pencari kerja.
     * GET /seeker/profile/create — route name: seeker.profile.create
     */
    public function create(Request $request)
    {
        $user = $request->user();

        if ($user->jobSeekerProfile()->exists()) {
            return redirect()
                ->route('seeker.profile.edit')
                ->with('info', 'Anda sudah memiliki profil. Silakan perbarui data profil Anda di bawah ini.');
        }

        return view('seeker.profile.create', compact('user'));
    }

    /**
     * CREATE (Store): Simpan data profil baru ke database.
     * POST /seeker/profile — route name: seeker.profile.store
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->jobSeekerProfile()->exists()) {
            return redirect()->route('seeker.profile.edit');
        }

        $validated = $request->validate([
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:500'],
            'education' => ['nullable', 'string', 'max:100'],
            'skills'    => ['nullable', 'string'],
            'cv'        => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'cv.mimes' => 'Berkas CV harus berformat PDF.',
            'cv.max'   => 'Ukuran berkas CV maksimal 2MB.',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'local');
        }

        JobSeekerProfile::create([
            'user_id'   => $user->id,
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'] ?? null,
            'education' => $validated['education'] ?? null,
            'skills'    => $validated['skills'] ?? null,
            'cv_path'   => $cvPath,
        ]);

        ActivityLog::record('create', 'Profil Pelamar', "Pencari kerja {$user->name} membuat profil baru.", $user);

        return redirect()
            ->route('seeker.profile.show')
            ->with('success', 'Profil pencari kerja berhasil dibuat.');
    }

    /**
     * UPDATE: Form edit profil pencari kerja.
     * GET /seeker/profile/edit — route name: seeker.profile.edit
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile(); // firstOrCreate

        return view('seeker.profile.edit', compact('user', 'profile'));
    }

    /**
     * UPDATE (Update): Simpan pembaruan data profil dan CV.
     * PUT /seeker/profile — route name: seeker.profile.update
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:500'],
            'education' => ['nullable', 'string', 'max:100'],
            'skills'    => ['nullable', 'string'],
            'cv'        => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
            'cv.mimes'       => 'Berkas CV harus berformat PDF.',
            'cv.max'         => 'Ukuran berkas CV maksimal 2MB.',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
        ]);

        $profileData = [
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'] ?? null,
            'education' => $validated['education'] ?? null,
            'skills'    => $validated['skills'] ?? null,
        ];

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'local');
            $profileData['cv_path'] = $cvPath;
        }

        $user->jobSeekerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        ActivityLog::record('update', 'Profil Pelamar', "Pencari kerja {$user->name} memperbarui data profil.", $user);

        return redirect()
            ->route('seeker.profile.show')
            ->with('success', 'Profil pencari kerja berhasil diperbarui.');
    }

    /**
     * DELETE: Hapus/reset data profil pencari kerja dan berkas CV.
     * DELETE /seeker/profile — route name: seeker.profile.destroy
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $profile = $user->jobSeekerProfile()->first();

        if ($profile) {
            if ($profile->cv_path && Storage::disk('local')->exists($profile->cv_path)) {
                Storage::disk('local')->delete($profile->cv_path);
            }
            $profile->delete();

            ActivityLog::record('delete', 'Profil Pelamar', "Pencari kerja {$user->name} menghapus data profil dan CV.", $user);
        }

        return redirect()
            ->route('seeker.profile.show')
            ->with('success', 'Data profil pencari kerja dan berkas CV berhasil dihapus.');
    }

    /**
     * Unduh berkas CV profil pencari kerja.
     * GET /seeker/profile/cv — route name: seeker.profile.cv
     */
    public function downloadCv(Request $request): StreamedResponse
    {
        $user = $request->user();
        $profile = $user->jobSeekerProfile;

        if (! $profile || ! $profile->cv_path || ! Storage::disk('local')->exists($profile->cv_path)) {
            abort(404, 'Berkas CV profil tidak ditemukan.');
        }

        $filename = 'CV_Profil_' . str($user->name)->slug('_') . '.pdf';

        return Storage::disk('local')->download($profile->cv_path, $filename);
    }
}
