<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\CourseTeacher;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index()
    {
        try {
            $teachers = Teacher::all();
            return TeacherResource::collection($teachers);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
                'code' => 'TEACHERS_SHOW_ALL_ERROR'
            ], 500);
        }
    }

    public function store(StoreTeacherRequest $request)
    {
        try {
            $teacher = Teacher::create($request->validated());

            return response()->json([
                'message' => 'Teacher inserted.',
                'teacher' => new TeacherResource($teacher) 
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, the teacher has not been added. Try later!',
                'code' => 'TEACHER_ADD_ERROR'
            ], 500);
        }
    }
        
    public function show(Teacher $teacher)
    {
        try {
            return new TeacherResource($teacher);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
                'code' => 'TEACHER_SHOW_ERROR'
            ], 500);
        }
    }

    public function update(StoreTeacherRequest $request, Teacher $teacher)
    {
        try {
            $teacher->fill($request->validated());

            $teacher->isDirty() ? $teacher->save() : null;

            return response()->json([
                'message' => $teacher->wasChanged() 
                    ? 'Teacher updated successfully.' 
                    : 'No changes detected.',
                'teacher' => new TeacherResource($teacher)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, the teacher has not been updated. Try later!',
                'code' => 'TEACHER_UPDATE_ERROR'
            ], 500);
        }
    }

    public function destroy(Teacher $teacher)
    {
        $verifyCourseTeacher = CourseTeacher::where('teacher_id', $teacher->id)->exists();

        if ($verifyCourseTeacher) {
            return response()->json([
                'error' => 'Conflict.',
                'message' => "The teacher is linked to at least one course.",
            ], 409);
        }

        try {
            $teacher->delete();
            return response()->json([
                'message' => "Excluded teacher.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, The teacher could not be deleted. Try later!',
                'code' => 'TEACHER_DELETE_ERROR'
            ], 500);
        }
    }
}
