<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN  = 'company_admin';
    public const ROLE_SEEKER = 'job_seeker';

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ============ Role Helpers ============

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSeeker(): bool
    {
        return $this->role === self::ROLE_SEEKER;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN  => 'Admin Perusahaan',
            self::ROLE_SEEKER => 'Pencari Kerja',
            default           => ucfirst($this->role ?? 'User'),
        };
    }

    // ============ Relasi ============

    // Satu job_seeker punya satu profil
    public function jobSeekerProfile()
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    public function profile(): JobSeekerProfile
    {
        return $this->jobSeekerProfile()->firstOrCreate([]);
    }

    // Lamaran pekerjaan milik user ini
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}