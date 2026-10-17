<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partnership extends Model
{
    use HasFactory;

    // ============ STATUS CONSTANTS ============
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Kontrak transisi status kerja sama:
     *   pending  → approved | archived
     *   approved → archived
     *   archived → (final)
     *
     * Pengajuan yang sudah disetujui tidak dapat dikembalikan ke `pending`,
     * dan arsip kemitraan bersifat final.
     */
    protected const ALLOWED_TRANSITIONS = [
        self::STATUS_PENDING  => [self::STATUS_APPROVED, self::STATUS_ARCHIVED],
        self::STATUS_APPROVED => [self::STATUS_ARCHIVED],
        self::STATUS_ARCHIVED => [],
    ];

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'type',
        'timeline',
        'message',
        'status',
        'admin_note',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'Disetujui / Aktif',
            'archived' => 'Arsip Kemitraan',
            default    => 'Menunggu Review',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'archived' => 'bg-slate-100 text-slate-800 border-slate-200',
            default    => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    }

    // ============ KONTRAK TRANSISI STATUS ============

    public static function allowedTransitionsFrom(string $status): array
    {
        return self::ALLOWED_TRANSITIONS[$status] ?? [];
    }

    /**
     * Menyimpan status yang sama tetap diizinkan (mis. hanya menambah catatan admin).
     */
    public function canTransitionTo(string $status): bool
    {
        if ($status === $this->status) {
            return true;
        }

        return in_array($status, self::allowedTransitionsFrom($this->status), true);
    }

    public function isFinal(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING  => 'Menunggu Review',
            self::STATUS_APPROVED => 'Disetujui / Aktif (Rekanan Resmi)',
            self::STATUS_ARCHIVED => 'Arsip Kemitraan (Nonaktif / Ditangguhkan)',
        ];
    }
}
