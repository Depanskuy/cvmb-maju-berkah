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
        'name', 'email', 'password',
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


    public function profile(): JobSeekerProfile
    {
        return $this->jobSeekerProfile()->firstOrCreate([]);
    }
}