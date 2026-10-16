<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    /**
     * Tampilkan daftar pengajuan kerja sama & kemitraan B2B.
     * GET /admin/kerja-sama — route name: admin.kerja-sama.index
     */
    public function index(Request $request)
    {
        $query = Partnership::latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $proposals = $query->get();

        $stats = [
            'total'    => Partnership::count(),
            'pending'  => Partnership::where('status', 'pending')->count(),
            'approved' => Partnership::where('status', 'approved')->count(),
            'archived' => Partnership::where('status', 'archived')->count(),
        ];

        return view('admin.kerja-sama.index', compact('stats', 'proposals'));
    }

    /**
     * Form publik pengajuan kerja sama di halaman /contact.
     * POST /contact/partnership
     */
    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'company_name'     => ['required', 'string', 'max:150'],
            'contact_name'     => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:150'],
            'phone'            => ['required', 'string', 'max:30'],
            'service_interest' => ['required', 'string', 'max:100'],
            'project_timeline' => ['nullable', 'string', 'max:100'],
            'project_details'  => ['required', 'string', 'min:10'],
        ], [
            'company_name.required'     => 'Nama perusahaan / instansi wajib diisi.',
            'contact_name.required'     => 'Nama lengkap PIC wajib diisi.',
            'email.required'            => 'Email resmi perusahaan wajib diisi.',
            'phone.required'            => 'Nomor telepon / WhatsApp PIC wajib diisi.',
            'service_interest.required' => 'Minat bidang layanan wajib dipilih.',
            'project_details.required'  => 'Detail kebutuhan bisnis wajib diisi.',
            'project_details.min'       => 'Detail kebutuhan minimal 10 karakter.',
        ]);

        $partnership = Partnership::create([
            'company_name' => $validated['company_name'],
            'contact_name' => $validated['contact_name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'],
            'type'         => $validated['service_interest'],
            'timeline'     => $validated['project_timeline'] ?? 'Standar',
            'message'      => $validated['project_details'],
            'status'       => 'pending',
        ]);

        ActivityLog::record(
            'create',
            'Pengajuan Kerja Sama',
            "Calon mitra '{$partnership->company_name}' (PIC: {$partnership->contact_name}) mengajukan proposal kerja sama di bidang {$partnership->type}."
        );

        return back()->with('success_contact', 'Permintaan konsultasi & kemitraan Anda berhasil dikirim! Tim representatif CV Maju Sukses akan menghubungi Anda dalam waktu 1x24 jam kerja.');
    }

    /**
     * Admin memperbarui status pengajuan kerja sama.
     * PATCH /admin/kerja-sama/{partnership}/status
     */
    public function updateStatus(Request $request, Partnership $partnership)
    {
        $validated = $request->validate([
            'status'     => ['required', 'in:pending,approved,archived'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $partnership->update([
            'status'     => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? $partnership->admin_note,
        ]);

        $statusLabel = match($partnership->status) {
            'approved' => 'Disetujui / Aktif',
            'archived' => 'Arsip Kemitraan',
            default    => 'Menunggu Review',
        };

        ActivityLog::record(
            'status_change',
            'Pengajuan Kerja Sama',
            "Admin mengubah status kemitraan '{$partnership->company_name}' menjadi '{$statusLabel}'."
        );

        return back()->with('success', "Status pengajuan kerja sama '{$partnership->company_name}' berhasil diperbarui menjadi {$statusLabel}.");
    }
}
