<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password'=>Hash::make('password')
        ]);
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password'=>Hash::make('password')

        ]);
        User::factory()->create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'password'=>Hash::make('password')

        ]);
        User::factory()->create([
            'name' => 'Test2',
            'email' => 'test2@test.com',
            'password'=>Hash::make('password')

        ]);
        User::factory()->create([
            'name' => 'Test3',
            'email' => 'test3@test.com',
            'password'=>Hash::make('password')

        ]);
    }
}
