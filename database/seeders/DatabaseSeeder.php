<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Course::factory(12)->create();
        \App\Models\Student::factory(25)->create();
        \App\Models\Registration::factory(30)->create();
    }
}
