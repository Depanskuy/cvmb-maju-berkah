<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobSeekerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Perusahaan
        User::firstOrCreate(
            ['email' => 'admin@majusukses.test'],
            [
                'name'     => 'Administrator Perusahaan',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        // 2. Pencari Kerja
        $seeker = User::firstOrCreate(
            ['email' => 'seeker@majusukses.test'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_SEEKER,
            ]
        );

        JobSeekerProfile::firstOrCreate(
            ['user_id' => $seeker->id],
            [
                'phone'     => '+62 812-3456-7890',
                'address'   => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
                'education' => 'S1 Sistem Informasi',
                'skills'    => 'PHP, Laravel, PostgreSQL, Tailwind CSS',
            ]
        );
    }
}
