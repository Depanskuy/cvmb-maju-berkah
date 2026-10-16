<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Tampilkan daftar proyek & portofolio CV Maju Sukses.
     * GET /admin/project — route name: admin.project.index
     */
    public function index(Request $request)
    {
        $query = Project::latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $projects = $query->get();

        $stats = [
            'total_projects' => Project::count(),
            'ongoing'        => Project::where('status', 'ongoing')->count(),
            'completed'      => Project::where('status', 'completed')->count(),
            'planning'       => Project::where('status', 'planning')->count(),
        ];

        return view('admin.project.index', compact('stats', 'projects'));
    }

    /**
     * Tambah proyek baru.
     * POST /admin/project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'client'      => ['required', 'string', 'max:150'],
            'category'    => ['required', 'string', 'max:100'],
            'budget'      => ['required', 'string', 'max:100'],
            'timeline'    => ['required', 'string', 'max:100'],
            'progress'    => ['required', 'integer', 'between:0,100'],
            'status'      => ['required', 'in:planning,ongoing,completed'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required'     => 'Nama project wajib diisi.',
            'client.required'   => 'Nama klien / instansi pemberi kerja wajib diisi.',
            'category.required' => 'Kategori bidang pekerjaan wajib diisi.',
            'budget.required'   => 'Estimasi nilai anggaran / kontrak wajib diisi.',
            'timeline.required' => 'Jadwal pelaksanaan wajib diisi.',
            'progress.between'  => 'Progres fisik harus berada di antara 0% hingga 100%.',
        ]);

        $project = Project::create($validated);

        ActivityLog::record(
            'create',
            'Manajemen Project',
            "Admin menambahkan proyek baru: '{$project->name}' (Klien: {$project->client}, Progress: {$project->progress}%)."
        );

        return back()->with('success', "Proyek '{$project->name}' berhasil ditambahkan ke daftar portofolio.");
    }

    /**
     * Update data proyek & progres fisik dari lapangan.
     * PUT /admin/project/{project}
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'client'      => ['required', 'string', 'max:150'],
            'category'    => ['required', 'string', 'max:100'],
            'budget'      => ['required', 'string', 'max:100'],
            'timeline'    => ['required', 'string', 'max:100'],
            'progress'    => ['required', 'integer', 'between:0,100'],
            'status'      => ['required', 'in:planning,ongoing,completed'],
            'description' => ['nullable', 'string'],
        ], [
            'progress.between' => 'Progres fisik harus di antara 0% hingga 100%.',
        ]);

        $oldProgress = $project->progress;
        $project->update($validated);

        ActivityLog::record(
            'update',
            'Manajemen Project',
            "Admin memperbarui progres proyek '{$project->name}' dari {$oldProgress}% menjadi {$project->progress}% (Status: {$project->status_label})."
        );

        return back()->with('success', "Progres dan data proyek '{$project->name}' berhasil diperbarui.");
    }

    /**
     * Hapus proyek.
     * DELETE /admin/project/{project}
     */
    public function destroy(Project $project)
    {
        $name = $project->name;
        $project->delete();

        ActivityLog::record(
            'delete',
            'Manajemen Project',
            "Admin menghapus proyek '{$name}' dari portofolio."
        );

        return back()->with('success', "Proyek '{$name}' berhasil dihapus.");
    }
}
