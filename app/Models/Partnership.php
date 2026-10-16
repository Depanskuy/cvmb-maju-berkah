<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partnership extends Model
{
    use HasFactory;

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
}
