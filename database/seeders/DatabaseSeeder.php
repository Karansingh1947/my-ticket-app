<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
            'name' => 'admin',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);
         // Create 5 users and 20 tickets

        \App\Models\User::factory(5)->create()->each(function ($user) {
            \App\Models\Ticket::factory(4)->create(['created_by' => $user->id]);
        });
    }
}
