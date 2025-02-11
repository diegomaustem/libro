<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Registration;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::all();
            return CourseResource::collection($courses);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
            ], 500);
        }  
    }

    public function store(StoreCourseRequest $request)
    {
        try {
            $course = Course::create($request->validated());

            return response()->json([
                'message' => 'Course inserted.',
                'course' => new CourseResource($course) 
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, the course has not been added. Try later!',
            ], 500);
        }
    }

    public function show(Course $course)
    {
        try {
            return new CourseResource($course);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
            ], 500);
        }
    }

    public function update(StoreCourseRequest $request, Course $course)
    {
        try {
            $course->update($request->validated());

            return response()->json([
                'message' => "Updated course.",
                'course' => new CourseResource($course)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, the course has not been updated. Try later!',
            ], 500);
        }
    }

    public function destroy(Course $course)
    {
        $verifyIfThereAreStudentsEnrolledInCourse = $this->verifyIfThereAreStudentsEnrolledInCourse($course->id);

        if (is_object($verifyIfThereAreStudentsEnrolledInCourse)) {
            return response()->json([
                'error' => 'Conflict.',
                'message' => "The course has students enrolled. It is necessary to close the registration for deletion.",
            ], 409);
        } 

        try {
            $course->delete();
            return response()->json([
                'message' => "Excluded course.", 
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, The course could not be deleted. Try later!',
            ], 500);
        }
    }

    private function verifyIfThereAreStudentsEnrolledInCourse($course)
    {
        $course = Registration::where('course_id', $course)->first();
        return $course;
    }
}
