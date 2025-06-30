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
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('fullname');
            $table->date('birth_date');
            $table->enum('shift', ['shift-1', 'shift-2', 'shift-3'])->default('shift-1');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('profile_photo')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->index(['role_id',  'created_at']);
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
