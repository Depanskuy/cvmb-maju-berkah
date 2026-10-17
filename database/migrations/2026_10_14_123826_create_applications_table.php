<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->foreignId('job_seeker_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone')->nullable();
            $table->text('applicant_address')->nullable();
            $table->string('education')->nullable();  
            $table->text('skills')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cv_path');
            $table->enum('status', ['pending', 'processing', 'accepted', 'rejected'])->default('pending');
            $table->text('review_note')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
