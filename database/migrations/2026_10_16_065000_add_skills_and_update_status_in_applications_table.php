<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'skills')) {
                $table->text('skills')->nullable()->after('education');
            }
        });

        // Update status constraint for PostgreSQL
        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_status_check");
        DB::statement("ALTER TABLE applications ADD CONSTRAINT applications_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'processing'::text, 'accepted'::text, 'rejected'::text]))");
        DB::statement("ALTER TABLE applications ALTER COLUMN status SET DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'skills')) {
                $table->dropColumn('skills');
            }
        });

        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_status_check");
        DB::statement("ALTER TABLE applications ADD CONSTRAINT applications_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'processing'::text, 'accepted'::text, 'rejected'::text]))");
        DB::statement("ALTER TABLE applications ALTER COLUMN status SET DEFAULT 'pending'");
    }
};

