<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\JobApplication;
use App\Models\Company;
use App\Models\Job;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
                ? redirect()->route('company.jobs.index')
                : redirect()->route('job-seeker.jobs.index');
        }

        $query = Job::publiclyVisible();

        // Filter keyword
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ILIKE', "%{$keyword}%")
                  ->orWhere('category', 'ILIKE', "%{$keyword}%")
                  ->orWhere('location', 'ILIKE', "%{$keyword}%");
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter tipe kerja
        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        // Filter lokasi
        if ($request->filled('location')) {
            $query->where('location', 'ILIKE', '%' . $request->input('location') . '%');
        }

        // Urutan hasil
        match ($request->input('sort', 'latest')) {
            'deadline' => $query->orderBy('deadline'),
            'title'    => $query->orderBy('title'),
            default    => $query->latest(),
        };

        $jobs = $query->paginate(9)->withQueryString();

        $categories = Job::publiclyVisible()
            ->select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return view('careers.index', compact('jobs', 'categories'));
    }

    /**
     * Detail lowongan.
     * GET /careers/{job} — route name: careers.show
     */
    public function show(Job $job)
    {
        // Pengguna yang sudah login diarahkan ke portal internal
        if (auth()->check()) {
            return auth()->user()->isAdmin()
                ? redirect()->route('company.jobs.show', $job)
                : redirect()->route('job-seeker.jobs.show', $job);
        }

        // Hanya tampilkan lowongan yang boleh dilihat publik
        abort_unless(
            $job->status === 'active' || $job->status === 'closed',
            404
        );

        $company = Company::current();

        return view('careers.show', compact('job', 'company'));
    }

    /**
     * Form lamaran lowongan.
     * GET /career/apply-job/{job} — route name: careers.apply.create
     */
    public function apply(Job $job)
    {
        // Hanya tampilkan lowongan yang boleh dilihat publik
        abort_unless(
            $job->status === 'active' || $job->status === 'closed',
            404
        );

        abort_unless(
            $job->isOpenForApplication(),
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

        return view('careers.apply-job', compact('job', 'user', 'profile'));
    }

    /**
     * Submit lamaran (wajib login sebagai pencari kerja).
     * POST /careers/{job}/apply — route name: careers.apply
     */
    public function storeApplication(StoreApplicationRequest $request, Job $job)
    {
        $user = $request->user();

        // BR-Role: Hanya job_seeker yang boleh melamar
        abort_unless(
            $user && $user->isSeeker(),
            403,
            'Hanya akun pencari kerja yang dapat mengirimkan berkas lamaran.'
        );

        // BR-03: Cek lowongan masih aktif & belum deadline
        abort_unless($job->isOpenForApplication(), 403, 'Lowongan sudah ditutup atau melewati deadline.');

        // BR-02: Cegah lamaran ganda by job_seeker_id atau email untuk lowongan yang sama
        // Lamaran yang sudah ditarik (withdrawn_at) tidak dihitung agar pelamar dapat melamar ulang.
        $exists = JobApplication::where('job_id', $job->id)
            ->whereNull('withdrawn_at')
            ->where(function ($q) use ($user) {
                $q->where('job_seeker_id', $user->id)
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

        // §12 poin 9: ketiga penulisan di bawah harus berhasil sebagai satu unit.
        try {
            DB::transaction(function () use (
                $request, $user, $job, $cvPath, $applicantName, $applicantEmail
            ) {
                JobApplication::create([
                    'job_seeker_id'     => $user->id,
                    'job_id'            => $job->id,
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
                    "Pelamar {$applicantName} mengirimkan berkas lamaran untuk posisi {$job->title}.",
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
            });
        } catch (\Throwable $e) {
            // Jangan tinggalkan berkas CV yatim bila penyimpanan data gagal.
            Storage::disk('local')->delete($cvPath);

            throw $e;
        }

        return redirect()
            ->route('job-seeker.applications.index')
            ->with('apply_success', 'Lamaran Anda untuk posisi "' . $job->title . '" berhasil dikirim! Anda dapat memantau statusnya di halaman Lamaran Saya.');
    }
}