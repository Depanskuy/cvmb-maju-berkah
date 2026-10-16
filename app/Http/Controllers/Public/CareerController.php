<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\JobListing;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Daftar lowongan aktif.
     * GET /careers — route name: careers.index
     */
    public function index(Request $request)
    {
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
        // Hanya tampilkan lowongan yang boleh dilihat publik
        abort_unless(
            $jobListing->status === 'active' || $jobListing->status === 'closed',
            404
        );

        $company = CompanyProfile::current();

        return view('careers.show', compact('jobListing', 'company'));
    }

    /**
     * Submit lamaran (guest).
     * POST /careers/{jobListing}/apply — route name: careers.apply.store
     */
    public function storeApplication(StoreApplicationRequest $request, JobListing $jobListing)
    {
        // BR-03: cek lowongan masih aktif & belum deadline
        abort_unless($jobListing->isOpenForApplication(), 403, 'Lowongan sudah ditutup atau melewati deadline.');

        // BR-02: cegah lamaran ganda by email + job
        $exists = Application::where('job_listing_id', $jobListing->id)
            ->where('applicant_email', $request->validated('applicant_email'))
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'applicant_email' => 'Email ini sudah pernah melamar lowongan ini.',
                ]);
        }

        // Upload CV
        $cvPath = $request->file('cv')->store('cvs', 'local');

        Application::create([
            'job_listing_id'    => $jobListing->id,
            'applicant_name'    => $request->validated('applicant_name'),
            'applicant_email'   => $request->validated('applicant_email'),
            'applicant_phone'   => $request->validated('applicant_phone'),
            'applicant_address' => $request->validated('applicant_address'),
            'education'         => $request->validated('education'),
            'skills'            => $request->validated('skills'),
            'cover_letter'      => $request->validated('cover_letter'),
            'cv_path'           => $cvPath,
            'status'            => 'pending',
        ]);

        return redirect()
            ->route('careers.show', $jobListing)
            ->with('apply_success', 'Lamaran Anda berhasil dikirim! Tim rekrutmen akan meninjau berkas Anda dan menghubungi via email/WhatsApp.');
    }
}