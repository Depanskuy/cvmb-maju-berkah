<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JobApplication extends Application
{
    use HasFactory;

    protected $table = 'applications';

    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_ACCEPTED   = 'accepted';
    public const STATUS_REJECTED   = 'rejected';

    protected $fillable = [
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
        'reference_code',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $application) {
            $application->reference_code ??= 'APP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        });
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id');
    }

    public function job()
    {
        return $this->jobListing();
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
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