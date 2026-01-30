<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@amine.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Editor
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@amine.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'editor',
        ]);

        // Create Regular User (Amine)
        User::create([
            'name' => 'Amine Resident',
            'email' => 'amine@amine.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        // Create some random questions
        \App\Models\Question::factory(20)->create();
    }
}
