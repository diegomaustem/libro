<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Course::factory(12)->create();
        \App\Models\Student::factory(25)->create();
        \App\Models\Registration::factory(30)->create();
        \App\Models\Teacher::factory(10)->create();

        try {            
            $this->call(CourseTeacherSeeder::class);
            
        } catch (\Exception $e) {
            $this->command->error('Seeder failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
