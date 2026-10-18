<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\JobSeekerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Perusahaan (company_admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@majuberkah.test'],
            [
                'name'     => 'Administrator Perusahaan',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        // Tautkan perusahaan dengan akun admin (contract §7.2: companies.user_id).
        $company = Company::where('slug', 'cv-maju-berkah')->first();
        if ($company && $company->user_id !== $admin->id) {
            $company->update(['user_id' => $admin->id]);
        }

        // 2. Pencari Kerja (job_seeker)
        $seeker = User::firstOrCreate(
            ['email' => 'seeker@majuberkah.test'],
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
