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
        Schema::create('payroll_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->decimal('salary', 15, 2); // Gaji pokok
            $table->decimal('alpha_deduction', 15, 2); // Potongan karena alpha
            $table->decimal('total_salary', 15, 2); // Gaji total setelah potongan
            $table->string('month'); // misal "January"
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
