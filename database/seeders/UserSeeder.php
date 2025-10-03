<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'username' => 'sadmin',
            'email' => 'sadmin@m4v.me',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Create Admin
        User::create([
            'username' => 'admin',
            'email' => 'admin@m4v.me',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Create Super Moderator
        User::create([
            'username' => 'smod',
            'email' => 'smod@m4v.me',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SUPER_MOD,
            'email_verified_at' => now(),
        ]);

        // Create Forum Moderator
        User::create([
            'username' => 'fmod',
            'email' => 'fmod@m4v.me',
            'password' => Hash::make('password'),
            'role' => User::ROLE_FORUM_MOD,
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        User::create([
            'username' => 'user',
            'email' => 'user@m4v.me',
            'password' => Hash::make('password'),
            'role' => User::ROLE_USER,
            'email_verified_at' => now(),
        ]);
    }
}
