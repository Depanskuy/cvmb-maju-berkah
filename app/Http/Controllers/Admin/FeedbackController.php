<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar feedback & evaluasi layanan.
     * GET /admin/feedback — route name: admin.feedback.index
     */
    public function index(Request $request)
    {
        $query = Feedback::latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->input('rating'));
        }

        $feedbacks = $query->get();

        $stats = [
            'total'          => Feedback::count(),
            'average_rating' => Feedback::count() > 0 ? number_format(Feedback::avg('rating'), 1) : '5.0',
            'unread'         => Feedback::where('status', 'Baru')->count(),
            'resolved'       => Feedback::where('status', 'Ditindaklanjuti')->count(),
        ];

        return view('admin.feedback.index', compact('stats', 'feedbacks'));
    }

    /**
     * Form kirim feedback dari portal pencari kerja.
     * POST /seeker/feedback
     */
    public function storeSeeker(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'rating'   => ['required', 'integer', 'between:1,5'],
            'message'  => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'category.required' => 'Kategori masukan wajib dipilih.',
            'rating.required'   => 'Rating bintang wajib dipilih (1-5).',
            'rating.between'    => 'Rating harus antara 1 sampai 5 bintang.',
            'message.required'  => 'Pesan masukan/saran wajib diisi.',
            'message.min'       => 'Pesan masukan minimal 5 karakter.',
        ]);

        $user = $request->user();

        $fb = Feedback::create([
            'user_id'  => $user?->id,
            'name'     => $user?->name ?? 'Pencari Kerja',
            'email'    => $user?->email,
            'category' => $validated['category'],
            'rating'   => $validated['rating'],
            'message'  => $validated['message'],
            'status'   => 'Baru',
        ]);

        ActivityLog::record(
            'create',
            'Feedback Layanan',
            "Pencari kerja {$fb->name} mengirimkan ulasan kepuasan ({$fb->rating}/5 bintang) kategori {$fb->category}.",
            $user
        );

        return back()->with('success_feedback', 'Terima kasih atas ulasan dan masukan Anda! Evaluasi Anda sangat berarti untuk kemajuan layanan kami.');
    }

    /**
     * Ubah status tindak lanjut masukan.
     * PATCH /admin/feedback/{feedback}/status
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $newStatus = $feedback->status === 'Baru' ? 'Ditindaklanjuti' : 'Baru';
        $feedback->update(['status' => $newStatus]);

        ActivityLog::record(
            'status_change',
            'Feedback Layanan',
            "Admin mengubah status masukan dari {$feedback->name} menjadi '{$newStatus}'."
        );

        return back()->with('success', "Status masukan dari {$feedback->name} ditandai sebagai {$newStatus}.");
    }

    /**
     * Hapus masukan.
     * DELETE /admin/feedback/{feedback}
     */
    public function destroy(Feedback $feedback)
    {
        $name = $feedback->name;
        $feedback->delete();

        ActivityLog::record(
            'delete',
            'Feedback Layanan',
            "Admin menghapus ulasan masukan dari '{$name}'."
        );

        return back()->with('success', "Masukan dari '{$name}' berhasil dihapus.");
    }
}
