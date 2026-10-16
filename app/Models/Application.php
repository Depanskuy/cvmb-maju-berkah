<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Application extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Default Laravel akan mencari "applications" (plural dari class),
     * jadi sebenarnya tidak wajib, tapi eksplisit lebih jelas.
     */
    protected $table = 'applications';

    // ============ STATUS CONSTANTS ============
    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_ACCEPTED   = 'accepted';
    public const STATUS_REJECTED   = 'rejected';

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'user_id',
        'job_listing_id',
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

    /**
     * Casting tipe data.
     */
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
     * User pencari kerja yang memiliki lamaran ini (jika login).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lamaran ini untuk lowongan apa.
     */
    public function jobListing()
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id');
    }

    /**
     * Alias pendek untuk ->jobListing(), biar di view bisa pakai ->job.
     */
    public function job()
    {
        return $this->jobListing();
    }

    // ============ SCOPES ============

    /**
     * Filter hanya lamaran dengan status tertentu.
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Filter hanya lamaran pending.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Filter lamaran berdasarkan job listing id.
     */
    public function scopeForJob(Builder $query, int $jobId): Builder
    {
        return $query->where('job_listing_id', $jobId);
    }

    // ============ ACCESSORS ============

    /**
     * Label Bahasa Indonesia untuk status.
     * Pemakaian di Blade: {{ $application->status_label }}
     */
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
            'interview'             => 'Interview',
            default                 => $this->status,
        };
    }

    /**
     * CSS class untuk badge status.
     * Pemakaian di Blade: <span class="badge badge--{{ $application->status_badge }}">
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->isWithdrawn()) {
            return 'neutral';
        }

        return match ($this->status) {
            self::STATUS_PENDING    => 'pending',
            self::STATUS_PROCESSING, 'interview' => 'processing',
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

    /**
     * Apakah lamaran masih bisa diubah admin?
     * (setelah accepted/rejected/withdrawn, dianggap final)
     */
    public function isFinal(): bool
    {
        return $this->isWithdrawn() || in_array($this->status, [
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
        ], true);
    }
}