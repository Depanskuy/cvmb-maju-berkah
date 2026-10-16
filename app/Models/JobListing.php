<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class JobListing extends Model
{
    use HasFactory;

    public const STATUS_DRAFT  = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    public const TYPE_FULL_TIME  = 'full_time';
    public const TYPE_PART_TIME  = 'part_time';
    public const TYPE_INTERNSHIP = 'internship';
    public const TYPE_CONTRACT   = 'contract';

    protected $fillable = [
        'title', 'slug', 'category',
        'description', 'requirements', 'location',
        'employment_type', 'salary_min', 'salary_max',
        'deadline', 'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline'   => 'date',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
        ];
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_listing_id');
    }

    // ============ Scopes ============

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->whereDate('deadline', '>=', now()->toDateString());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // ============ Helper ============

    public function isOpenForApplication(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->deadline->endOfDay()->isFuture();
    }

    public function canAcceptApplications(): bool
    {
        return $this->isOpenForApplication();
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return match ($this->employment_type) {
            self::TYPE_FULL_TIME  => 'Penuh Waktu',
            self::TYPE_PART_TIME  => 'Paruh Waktu',
            self::TYPE_INTERNSHIP => 'Magang',
            self::TYPE_CONTRACT   => 'Kontrak',
            default               => $this->employment_type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT  => 'Draf',
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_CLOSED => 'Ditutup',
            default             => $this->status,
        };
    }
}