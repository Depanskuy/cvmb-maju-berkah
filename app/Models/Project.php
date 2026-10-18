<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    // ============ STATUS CONSTANTS ============
    public const STATUS_PLANNING  = 'planning';
    public const STATUS_ONGOING   = 'ongoing';
    public const STATUS_COMPLETED = 'completed';

    /**
     * Kontrak transisi status proyek (satu arah):
     *   planning  → ongoing | completed
     *   ongoing   → completed
     *   completed → (final)
     */
    protected const ALLOWED_TRANSITIONS = [
        self::STATUS_PLANNING  => [self::STATUS_ONGOING, self::STATUS_COMPLETED],
        self::STATUS_ONGOING   => [self::STATUS_COMPLETED],
        self::STATUS_COMPLETED => [],
    ];

    protected $fillable = [
        'partnership_id',
        'name',
        'client',
        'category',
        'progress',
        'timeline',
        'status',
        'budget',
        'description',
    ];

    protected $casts = [
        'progress' => 'integer',
    ];

    public function partnership(): BelongsTo
    {
        return $this->belongsTo(Partnership::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Selesai',
            'planning'  => 'Tahap Persiapan',
            default     => 'Sedang Berjalan',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'planning'  => 'bg-amber-100 text-amber-800 border-amber-200',
            default     => 'bg-sky-100 text-sky-800 border-sky-200',
        };
    }

    // ============ KONTRAK TRANSISI STATUS ============

    public static function allowedTransitionsFrom(string $status): array
    {
        return self::ALLOWED_TRANSITIONS[$status] ?? [];
    }

    /**
     * Menyimpan status yang sama tetap diizinkan (mis. hanya memperbarui progres).
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
        return $this->status === self::STATUS_COMPLETED;
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PLANNING  => 'Tahap Persiapan',
            self::STATUS_ONGOING   => 'Sedang Berjalan',
            self::STATUS_COMPLETED => 'Selesai',
        ];
    }
}
