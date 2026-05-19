<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Single admin account (only one admin in the system)
        User::updateOrCreate(
            ['email' => 'anshbrock200@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'anshbrock',
                'password' => Hash::make('123456'),
                'role' => User::ROLE_ADMIN,
            ]
        );
    }
}
