<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'fullname' => 'Admin',
                'email' => 'adminwinni@gmail.com',
                'password' => bcrypt('admin123'),
                'role_id' => 6,
                'birth_date' => '1990-01-01',
                'shift' => 'shift-1',
                'address' => '123 Admin St, Admin City',
                'phone' => '1234567890',
                'profile_photo' => 'default.png',
                'is_active' => 1,
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
