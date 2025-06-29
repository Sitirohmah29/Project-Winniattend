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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('profile_photo')->nullable();
            $table->boolean('is_active')->default(1);
            $table->dateTime('email_verified_at')->nullable();
            $table->enum('role', ['admin', 'karyawan'])->default('karyawan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'birth_date', 'phone', 'address', 'profile_photo']);
        });
    }
};
