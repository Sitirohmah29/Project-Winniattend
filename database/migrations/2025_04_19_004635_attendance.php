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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('punch_in');
            $table->dateTime('punch_out')->nullable();
            $table->string('shift')->nullable();         
            $table->string('punch_in_location');
            $table->string('punch_out_location')->nullable();
            $table->string('punch_in_photo')->nullable();
            $table->string('punch_out_photo')->nullable();
            $table->string('status');
            $table->boolean('is_late')->default(false);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
