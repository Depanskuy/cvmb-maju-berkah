<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

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
}
