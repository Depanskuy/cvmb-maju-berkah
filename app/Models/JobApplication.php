<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'applications';

    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_ACCEPTED   = 'accepted';
    public const STATUS_REJECTED   = 'rejected';

    protected $fillable = [
        'job_listing_id', 'job_seeker_id', 'cover_letter',
        'cv_path', 'status', 'review_note', 'withdrawn_at',
    ];

    protected function casts(): array
    {
        return [
            'withdrawn_at' => 'datetime',
        ];
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id');
    }

    public function job()
    {
        return $this->jobListing();
    }

    public function jobSeeker()
    {
        return $this->belongsTo(User::class, 'job_seeker_id');
    }

    // Helper: ambil profil pelamar lewat relasi
    public function jobSeekerProfile()
    {
        return $this->hasOneThrough(
            JobSeekerProfile::class,
            User::class,
            'id',           // PK users
            'user_id',      // FK di job_seeker_profiles
            'job_seeker_id',// FK di applications → users
            'id'            // PK users
        );
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('withdrawn_at');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isEditableBySeeker(): bool
    {
        return $this->status === self::STATUS_PENDING
            && is_null($this->withdrawn_at);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING    => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_ACCEPTED   => 'Diterima',
            self::STATUS_REJECTED   => 'Ditolak',
            default                 => $this->status,
        };
    }
}