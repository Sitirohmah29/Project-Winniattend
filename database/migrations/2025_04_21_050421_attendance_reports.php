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
         Schema::create('attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('working_days')->nullable(); // Total Working Days
            $table->string('total_overtime')->nullable(); // Total Overtime (e.g. "8 hours")
            $table->string('total_work_duration')->nullable(); // Total Work Duration (e.g. "176 hours")
            $table->string('month')->nullable(); // e.g. "January"
            $table->timestamps();
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
