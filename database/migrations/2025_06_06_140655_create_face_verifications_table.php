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
        Schema::create('face_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['verified', 'failed', 'pending'])->default('pending');
            $table->integer('blink_count')->default(0);
            
            // Tambahan field untuk challenge baru
            $table->boolean('blink_passed')->default(false);
            $table->boolean('head_movement_passed')->default(false);
            $table->boolean('mouth_open_passed')->default(false);
            $table->json('challenges')->nullable(); // Untuk menyimpan detail semua challenges
            
            $table->timestamp('verified_at')->nullable();
            $table->longText('face_data')->nullable(); // Untuk menyimpan face encoding/landmarks jika diperlukan
            $table->string('verification_method')->default('multi_challenge'); // Diubah dari blink_detection
            $table->boolean('is_active')->default(true);
            $table->string('failure_reason')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'status']);
            $table->index(['verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_verifications');
    }
};
