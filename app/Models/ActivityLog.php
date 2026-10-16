<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'causer_name',
        'causer_role',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aktivitas baru ke dalam database secara otomatis.
     */
    public static function record(
        string $action,
        string $module,
        string $description,
        ?User $user = null,
        array $properties = []
    ): self {
        $user = $user ?? auth()->user();

        $roleLabel = match ($user?->role) {
            'company_admin' => 'Admin Perusahaan',
            'job_seeker'    => 'Pencari Kerja',
            default         => 'Pengunjung / Sistem',
        };

        return static::create([
            'user_id'     => $user?->id,
            'causer_name' => $user?->name ?? 'Sistem Otomatis',
            'causer_role' => $roleLabel,
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => request()?->ip() ?? '127.0.0.1',
            'user_agent'  => request()?->userAgent(),
            'properties'  => !empty($properties) ? $properties : null,
        ]);
    }

    /**
     * Helper badge warna status aksi.
     */
    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'create'        => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'update'        => 'bg-blue-100 text-blue-800 border-blue-200',
            'delete'        => 'bg-rose-100 text-rose-800 border-rose-200',
            'status_change' => 'bg-amber-100 text-amber-800 border-amber-200',
            'auth'          => 'bg-purple-100 text-purple-800 border-purple-200',
            'withdraw'      => 'bg-slate-100 text-slate-800 border-slate-200',
            default         => 'bg-surface-container-high text-on-surface border-outline-variant/30',
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create'        => 'Tambah Data',
            'update'        => 'Pembaruan',
            'delete'        => 'Hapus Data',
            'status_change' => 'Ubah Status',
            'auth'          => 'Autentikasi',
            'withdraw'      => 'Pembatalan',
            default         => ucfirst($this->action),
        };
    }
}
