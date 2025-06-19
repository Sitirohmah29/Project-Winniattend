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
            $table->foreignId('ser_roles_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('check_in');
            $table->dateTime('check_out');
            $table->string('shift')->nullable();         
            $table->string('check_in_location');
            $table->string('check_out_location');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('status');
            $table->string('permission');
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
