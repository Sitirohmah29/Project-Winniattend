<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Laravel Developer', 'salary' => 8000000],
            ['name' => 'Fullstack Developer', 'salary' => 10000000],
            ['name' => 'Copy Writer', 'salary' => 6000000],
            ['name' => 'Frontend Developer', 'salary' => 7500000],
            ['name' => 'Backend Developer', 'salary' => 8500000],
            ['name' => 'Admin', 'salary' => 4000000],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
