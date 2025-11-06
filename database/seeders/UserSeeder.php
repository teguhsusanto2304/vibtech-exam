<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 fake users
        User::factory()->count(200)->create();

        // Optionally, create an admin/test user
        User::factory()->create([
            'email' => 'admin@example.com',
            'name' => 'Administrator',
            'company' => 'Vibtech Genesis',
            'data_status' => 'active',
            'attempts_used' => 0,
            'last_score' => 100.00,
            'last_outcome' => 'Pass',
            'last_attempt_date' => now(),
            'password' => bcrypt('password'),
        ]);
    }
}
