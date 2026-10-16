<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan riwayat log aktivitas sistem secara transparan.
     * GET /admin/log — route name: admin.log.index
     */
    public function index(Request $request)
    {
        $query = ActivityLog::latest();

        // 1. Filter pencarian teks
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('causer_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // 2. Filter modul
        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        // 3. Filter aksi
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // 4. Filter peran pelaku
        if ($request->filled('role')) {
            $query->where('causer_role', $request->input('role'));
        }

        $logs = $query->paginate(15)->withQueryString();

        $stats = [
            'total'      => ActivityLog::count(),
            'admin'      => ActivityLog::where('causer_role', 'Admin Perusahaan')->count(),
            'seeker'     => ActivityLog::where('causer_role', 'Pencari Kerja')->count(),
            'today'      => ActivityLog::whereDate('created_at', today())->count(),
        ];

        $modules = ActivityLog::distinct()->pluck('module')->filter()->values();

        return view('admin.log.index', compact('logs', 'stats', 'modules'));
    }
}
