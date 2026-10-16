<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\JobListing;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Daftar lowongan aktif.
     * GET /careers — route name: careers.index
     */
    public function index(Request $request)
    {
        // Pengguna yang sudah login diarahkan ke portal sesuai role
        if (auth()->check()) {
            return auth()->user()->isAdmin()
                ? redirect()->route('admin.lowongan.index')
                : redirect()->route('seeker.jobs');
        }

        $query = JobListing::publiclyVisible()->latest();

        // Filter keyword
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ILIKE', "%{$keyword}%")
                  ->orWhere('category', 'ILIKE', "%{$keyword}%")
                  ->orWhere('location', 'ILIKE', "%{$keyword}%");
            });
        }

        // Filter tipe kerja
        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        // Filter lokasi
        if ($request->filled('location')) {
            $query->where('location', 'ILIKE', '%' . $request->input('location') . '%');
        }

        $jobs = $query->paginate(9)->withQueryString();

        return view('careers.index', compact('jobs'));
    }

    /**
     * Detail lowongan.
     * GET /careers/{jobListing} — route name: careers.show
     */
    public function show(JobListing $jobListing)
    {
        // Pengguna yang sudah login diarahkan ke portal internal
        if (auth()->check()) {
            return auth()->user()->isAdmin()
                ? redirect()->route('admin.lowongan.show', $jobListing)
                : redirect()->route('seeker.jobs.show', $jobListing);
        }

        // Hanya tampilkan lowongan yang boleh dilihat publik
        abort_unless(
            $jobListing->status === 'active' || $jobListing->status === 'closed',
            404
        );

        $company = CompanyProfile::current();

        return view('careers.show', compact('jobListing', 'company'));
    }

    /**
     * Form lamaran lowongan.
     * GET /career/apply-job/{jobListing} — route name: careers.apply.create
     */
    public function apply(JobListing $jobListing)
    {
        // Hanya tampilkan lowongan yang boleh dilihat publik
        abort_unless(
            $jobListing->status === 'active' || $jobListing->status === 'closed',
            404
        );

        abort_unless(
            $jobListing->isOpenForApplication(),
            403,
            'Lowongan sudah ditutup atau melewati deadline.'
        );

        // Pengunjung wajib login sebelum melamar
        if (! auth()->check()) {
            return redirect()->guest(route('login'))
                ->with('info', 'Silakan login terlebih dahulu sebagai pencari kerja untuk melamar posisi ini.');
        }

        // Admin perusahaan dilarang melamar
        abort_if(
            auth()->user()->isAdmin(),
            403,
            'Akun Admin Perusahaan tidak dapat melamar lowongan kerja. Silakan gunakan akun Pencari Kerja.'
        );

        $user = auth()->user();
        $profile = $user->profile();

        return view('careers.apply-job', compact('jobListing', 'user', 'profile'));
    }

    /**
     * Submit lamaran (wajib login sebagai pencari kerja).
     * POST /careers/{jobListing}/apply — route name: careers.apply.store
     */
    public function storeApplication(StoreApplicationRequest $request, JobListing $jobListing)
    {
        $user = $request->user();

        // BR-Role: Hanya job_seeker yang boleh melamar
        abort_unless(
            $user && $user->isSeeker(),
            403,
            'Hanya akun pencari kerja yang dapat mengirimkan berkas lamaran.'
        );

        // BR-03: Cek lowongan masih aktif & belum deadline
        abort_unless($jobListing->isOpenForApplication(), 403, 'Lowongan sudah ditutup atau melewati deadline.');

        // BR-02: Cegah lamaran ganda by user_id atau email untuk lowongan yang sama
        $exists = Application::where('job_listing_id', $jobListing->id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('applicant_email', $user->email);
            })
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'applicant_email' => 'Anda sudah pernah mengirimkan lamaran untuk posisi ini.',
                ]);
        }

        // Upload berkas CV
        $cvPath = $request->file('cv')->store('cvs', 'local');

        // Pastikan nama dan email terikat pada akun pencari kerja yang login
        $applicantName = $request->validated('applicant_name', $user->name);
        $applicantEmail = $user->email;

        $application = Application::create([
            'user_id'           => $user->id,
            'job_listing_id'    => $jobListing->id,
            'applicant_name'    => $applicantName,
            'applicant_email'   => $applicantEmail,
            'applicant_phone'   => $request->validated('applicant_phone'),
            'applicant_address' => $request->validated('applicant_address'),
            'education'         => $request->validated('education'),
            'skills'            => $request->validated('skills'),
            'cover_letter'      => $request->validated('cover_letter'),
            'cv_path'           => $cvPath,
            'status'            => 'pending',
        ]);

        ActivityLog::record(
            'create',
            'Lamaran Masuk',
            "Pelamar {$applicantName} mengirimkan berkas lamaran untuk posisi {$jobListing->title}.",
            $user
        );

        // Simpan / update profil pencari kerja agar memudahkan lamaran berikutnya
        $user->jobSeekerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone'     => $request->validated('applicant_phone'),
                'address'   => $request->validated('applicant_address'),
                'education' => $request->validated('education'),
                'skills'    => $request->validated('skills'),
                'cv_path'   => $cvPath,
            ]
        );

        return redirect()
            ->route('seeker.applications')
            ->with('apply_success', 'Lamaran Anda untuk posisi "' . $jobListing->title . '" berhasil dikirim! Anda dapat memantau statusnya di halaman Lamaran Saya.');
    }
}