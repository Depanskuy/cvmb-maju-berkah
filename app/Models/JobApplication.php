<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model `JobApplication` (tabel `applications`) — lamaran kerja.
 * Kontrak blueprint §7.5 dan §8.
 *
 * Catatan: model ini sengaja dinamai `JobApplication` sesuai rekomendasi
 * blueprint §8, namun nama tabel tetap `applications` (§7.5).
 */
class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'applications';

    // ============ STATUS CONSTANTS ============
    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_ACCEPTED   = 'accepted';
    public const STATUS_REJECTED   = 'rejected';

    protected $fillable = [
        'job_seeker_id',
        'job_id',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'applicant_address',
        'education',
        'skills',
        'cover_letter',
        'cv_path',
        'status',
        'review_note',
        'withdrawn_at',
    ];

    protected function casts(): array
    {
        return [
            'withdrawn_at' => 'datetime',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    // ============ RELASI ============

    /**
     * Akun pencari kerja pemilik lamaran ini.
     */
    public function jobSeeker()
    {
        return $this->belongsTo(User::class, 'job_seeker_id');
    }

    /**
     * Lowongan yang dilamar.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    // ============ SCOPES ============

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeForJob(Builder $query, int $jobId): Builder
    {
        return $query->where('job_id', $jobId);
    }

    /**
     * Lamaran aktif (belum ditarik).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('withdrawn_at');
    }

    // ============ ACCESSORS ============

    public function getStatusLabelAttribute(): string
    {
        if ($this->isWithdrawn()) {
            return 'Dibatalkan';
        }

        return match ($this->status) {
            self::STATUS_PENDING    => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_ACCEPTED   => 'Diterima',
            self::STATUS_REJECTED   => 'Ditolak',
            default                 => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->isWithdrawn()) {
            return 'neutral';
        }

        return match ($this->status) {
            self::STATUS_PENDING    => 'pending',
            self::STATUS_PROCESSING => 'processing',
            self::STATUS_ACCEPTED   => 'accepted',
            self::STATUS_REJECTED   => 'rejected',
            default                 => 'draft',
        };
    }

    // ============ HELPERS ============

    public function isWithdrawn(): bool
    {
        return ! is_null($this->withdrawn_at);
    }

    public function canBeWithdrawn(): bool
    {
        return $this->status === self::STATUS_PENDING && ! $this->isWithdrawn();
    }

    public function isFinal(): bool
    {
        return $this->isWithdrawn() || in_array($this->status, [
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
        ], true);
    }
}
