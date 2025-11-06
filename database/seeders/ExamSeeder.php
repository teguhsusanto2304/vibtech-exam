<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $statuses = ['draft', 'published', 'archived'];

        // 1. Static Data (For guaranteed records)
        Exam::create([
            'title' => 'Introduction to Laravel',
            'description' => 'A comprehensive exam covering Laravel basics, Eloquent, and routing.',
            'questions' => 25,
            'duration' => 45, // 45 minutes
            'data_status' => 'published',
            'last_modified' => Carbon::now(),
        ]);

        // 2. Dynamic Data (Using Faker for bulk records)
        for ($i = 0; $i < 10; $i++) {
            $status = $faker->randomElement($statuses);
            
            Exam::create([
                'title' => $faker->catchPhrase . ' Assessment',
                'description' => $faker->paragraph(3),
                'questions' => $faker->numberBetween(10, 50),
                'duration' => $faker->numberBetween(20, 90),
                'data_status' => $status,
                'last_modified' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}