<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Samakan nilai status lamaran dengan kontrak blueprint
     * (hanya pending, processing, accepted, rejected) dan ganti unique
     * lama dengan partial unique index agar pelamar dapat melamar ulang
     * setelah menarik lamaran.
     */
    public function up(): void
    {
        // 1. Normalisasi nilai status lama agar sesuai kontrak blueprint.
        DB::table('applications')->where('status', 'menunggu')->update(['status' => 'pending']);
        DB::table('applications')->where('status', 'interview')->update(['status' => 'processing']);

        // 2. Batasi nilai status ke empat nilai resmi.
        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_status_check");
        DB::statement("ALTER TABLE applications ADD CONSTRAINT applications_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'processing'::text, 'accepted'::text, 'rejected'::text]))");
        DB::statement("ALTER TABLE applications ALTER COLUMN status SET DEFAULT 'pending'");

        // 3. Ganti unique (job_listing_id, applicant_email) dengan partial unique index
        //    (job_listing_id, user_id) khusus lamaran aktif. Lamaran yang sudah ditarik
        //    (withdrawn_at terisi) tidak dihitung sehingga pelamar dapat melamar ulang.
        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_job_listing_id_applicant_email_unique");
        DB::statement("DROP INDEX IF EXISTS applications_job_listing_id_applicant_email_unique");
        DB::statement("DROP INDEX IF EXISTS applications_job_listing_id_user_id_active_unique");
        DB::statement("CREATE UNIQUE INDEX applications_job_listing_id_user_id_active_unique ON applications (job_listing_id, user_id) WHERE withdrawn_at IS NULL AND user_id IS NOT NULL");
    }

    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS applications_job_listing_id_user_id_active_unique");

        // Kembalikan ke unique lama (job_listing_id, applicant_email).
        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS applications_job_listing_id_applicant_email_unique ON applications (job_listing_id, applicant_email)");

        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_status_check");
        DB::statement("ALTER TABLE applications ADD CONSTRAINT applications_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'processing'::text, 'accepted'::text, 'rejected'::text, 'menunggu'::text, 'interview'::text]))");
    }
};
