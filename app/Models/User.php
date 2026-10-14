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

    // ============ Relasi ============

    // Satu job_seeker punya satu profil
    public function jobSeekerProfile()
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    // Semua lamaran yang dikirim user ini
    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_seeker_id');
    }

    // ============ Helper role ============

    public function isCompanyAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isJobSeeker(): bool
    {
        return $this->role === self::ROLE_SEEKER;
    }

    // Shortcut: ambil atau buat profil kosong
    public function profile(): JobSeekerProfile
    {
        return $this->jobSeekerProfile()->firstOrCreate([]);
    }
}