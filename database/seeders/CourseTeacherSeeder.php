<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $courseIds = Course::pluck('id')->toArray();
        $teacherIds = Teacher::pluck('id')->toArray();

        for ($i = 0; $i < 18; $i++) {
            $randomCourseId = fake()->randomElement($courseIds);
            $randomTeacherId = fake()->randomElement($teacherIds);

            if (!CourseTeacher::where('course_id', $randomCourseId)
                 ->where('teacher_id', $randomTeacherId)
                 ->exists()) {
    
                CourseTeacher::create([
                    'course_id' => $randomCourseId,
                    'teacher_id' => $randomTeacherId
                ]);
            }
        }
    }
}