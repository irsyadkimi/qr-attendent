<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin PRM WG',
            'email' => 'admin@prmwg.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'can_delete' => true,
            'can_create_event' => true,
            'can_manage_users' => true,
        ]);

        User::create([
            'name' => 'Operator Test',
            'email' => 'operator@prmwg.com',
            'password' => Hash::make('operator123'),
            'role' => 'operator',
            'can_delete' => false,
            'can_create_event' => true,
            'can_manage_users' => false,
        ]);
    }
}
