<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@eliteskisclub.com',
            'password' => 'password',
            'role' => AdminRole::SuperAdmin,
        ]);
    }
}
