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
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->string('type'); // Bidang Kemitraan / Layanan
            $table->string('timeline')->nullable(); // Skala proyek / waktu
            $table->text('message');
            $table->string('status')->default('pending'); // pending, approved, archived
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partnership_id')->nullable()->constrained('partnerships')->nullOnDelete();
            $table->string('name');
            $table->string('client');
            $table->string('category');
            $table->integer('progress')->default(0); // 0 - 100
            $table->string('timeline');
            $table->string('status')->default('ongoing'); // planning, ongoing, completed
            $table->string('budget');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('category'); // Proses Rekrutmen, Layanan Proyek, Portal Website, Pelayanan Kantor
            $table->unsignedTinyInteger('rating'); // 1 - 5
            $table->text('message');
            $table->string('status')->default('Baru'); // Baru, Ditindaklanjuti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('partnerships');
    }
};
