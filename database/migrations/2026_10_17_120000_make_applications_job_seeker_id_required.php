<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * §7.5 blueprint: setiap lamaran harus terikat pada satu akun pencari kerja.
     *
     * Kolom job_seeker_id sebelumnya nullable karena masih ada sisa alur lamaran
     * publik lama (pengunjung mengisi form tanpa login). Sejak alur lamaran
     * diwajibkan login sebagai job_seeker, kolom ini dapat dijadikan NOT NULL.
     *
     * Langkah:
     * 1. Bersihkan lamaran legacy yang tidak memiliki pemilik.
     * 2. Jadikan job_seeker_id NOT NULL dan ikut terhapus bila akun dihapus.
     * 3. Sederhanakan partial unique index (syarat "IS NOT NULL" tidak lagi perlu).
     */
    public function up(): void
    {
        // 1. Bersihkan lamaran legacy tanpa pemilik.
        DB::table('applications')->whereNull('job_seeker_id')->delete();

        // 2. Wajib terisi + cascade on delete.
        DB::statement('ALTER TABLE applications ALTER COLUMN job_seeker_id SET NOT NULL');
        DB::statement('ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_job_seeker_id_foreign');
        DB::statement(
            'ALTER TABLE applications ADD CONSTRAINT applications_job_seeker_id_foreign '
            . 'FOREIGN KEY (job_seeker_id) REFERENCES users(id) ON DELETE CASCADE'
        );

        // 3. Anti-lamaran-ganda tetap berlaku, kini cukup syarat withdrawn_at.
        DB::statement('DROP INDEX IF EXISTS applications_job_id_job_seeker_id_active_unique');
        DB::statement(
            'CREATE UNIQUE INDEX applications_job_id_job_seeker_id_active_unique '
            . 'ON applications (job_id, job_seeker_id) WHERE withdrawn_at IS NULL'
        );
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS applications_job_id_job_seeker_id_active_unique');
        DB::statement(
            'CREATE UNIQUE INDEX applications_job_id_job_seeker_id_active_unique '
            . 'ON applications (job_id, job_seeker_id) WHERE withdrawn_at IS NULL AND job_seeker_id IS NOT NULL'
        );

        DB::statement('ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_job_seeker_id_foreign');
        DB::statement(
            'ALTER TABLE applications ADD CONSTRAINT applications_job_seeker_id_foreign '
            . 'FOREIGN KEY (job_seeker_id) REFERENCES users(id) ON DELETE SET NULL'
        );
        DB::statement('ALTER TABLE applications ALTER COLUMN job_seeker_id DROP NOT NULL');
    }
};
