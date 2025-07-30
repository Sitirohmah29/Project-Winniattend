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
            ['name' => 'Laravel Developer', 'salary_perday' => 350000],
            ['name' => 'Fullstack Developer', 'salary_perday' => 385000],
            ['name' => 'Copy Writer', 'salary_perday' => 250000],
            ['name' => 'Frontend Developer', 'salary_perday' => 290000],
            ['name' => 'Backend Developer', 'salary_perday' => 325000],
            ['name' => 'Admin', 'salary_perday' => 150000],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
