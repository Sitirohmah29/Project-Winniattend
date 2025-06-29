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
        // Tabel untuk menyimpan data registrasi wajah
        Schema::create('face_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('image_path');
            $table->string('image_name');
            $table->text('metadata')->nullable(); // Store additional info like image dimensions, file size, etc.
            $table->timestamp('captured_at');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id',  'created_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('face_verifications', function (Blueprint $table) {
            $table->dropForeign(['face_registration_id']);
            $table->dropColumn(['face_registration_id', 'similarity_score', 'threshold']);
        });
        
        Schema::dropIfExists('face_registrations');
    }
};