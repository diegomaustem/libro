<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Registration;

class EnrolledPerCourseController extends Controller
{
    public function show($courseId)
    {
        try {
            if (!$course = Course::find($courseId)) return response()->json(['message' => 'Course not found'], 404);

            $students = Registration::where('course_id', $course->id)
                ->join('students', 'registrations.student_id', '=', 'students.id')
                ->select(
                    'students.name',
                    'students.email',
                    'students.gender'
                )->get();

            return response()->json([
                'course' => $course->title,
                'students' => $students->map(function($student) {
                    return [
                        'name' => $student->name,
                        'email' => $student->email,
                        'gender' => $student->gender
                    ];
                })
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
                'code' => 'ENROLLED_PER_COURSE_ERROR'
            ], 500);
        }
    }
}
