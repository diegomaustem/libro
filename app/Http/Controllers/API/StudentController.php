<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('query') && $request->query('query') === 'filters') {
            $students = $this->getStudentsByFilters();
            return response()->json($students);
        }

        if (!empty($request->query())) {
            $fieldsQuery = $this->organizeQueryParameters($request->query());

            if(!is_null($fieldsQuery)) {
                try {
                    $query = Student::query();

                    foreach ($fieldsQuery as $filter) {
                        $query->orWhere($filter['field'], 'ILIKE', '%' . $filter['parameter'] . '%');
                    }

                    $students = $query->get();
                    return StudentResource::collection($students);
                } catch (\Throwable $th) {
                    return response()->json([
                        'error' => 'Ops, query failure.',
                    ], 500);
                }
            } else {
                return response()->json([
                    'error' => 'Parameter not found.'
                ], 400);
            }
        }

        try {
            $students = Student::all();
            return StudentResource::collection($students);  
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Ops, query failure.',
                'code' => 'STUDENT_SHOW_ALL_ERROR'
            ], 500);
        }
    }

    public function store(StoreStudentRequest $request)
    {
        try {
            $student = Student::create($request->validated());
            return response()->json([
                'mensagem' => 'Student inserted.',
                'student' => new StudentResource($student) 
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, student cannot be inserted. Try later!',
                'code' => 'STUDENT_ADD_ERROR'
            ], 500);
        }
    }

    public function show(Student $student)
    {
        try {
            return new StudentResource($student);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
                'code' => 'STUDENT_SHOW_ERROR'
            ], 500);
        }
    }

    public function update(StoreStudentRequest $request, Student $student)
    {
        try {
            $student->update($request->validated());

            return response()->json([
                'message' => "Student updated.",
                'curso' => new StudentResource($student)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
                'code' => 'STUDENT_UPDATE_ERROR'
            ], 500);
        }
    }

    public function destroy(Student $student)
    {
        $verifyStudentRegistration = Registration::where('student_id', $student->id)->exits();

        if ($verifyStudentRegistration) {
            return response()->json([
                'error' => 'Conflict.',
                'message' => "The student is open enrollment. It is necessary to close the registration for deletion.",
            ], 409);
        } 

        try {
            $student->delete();
            return response()->json([
                'message' => "Excluded student.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, The student could not be deleted. Try later!',
                'code' => 'STUDENT_DELETE_ERROR'
            ], 500);
        }
    }

    private function organizeQueryParameters($queryParams)
    {
        $fieldsQuery = array_filter([
            Arr::get($queryParams, 'name') ? ['field' => 'name', 'parameter' => $queryParams['name']] : null,
            Arr::get($queryParams, 'email') ? ['field' => 'email', 'parameter' => $queryParams['email']] : null
        ]);

        return !empty($fieldsQuery) ? $fieldsQuery : null;
    }

    private function getStudentsByFilters()
    {
        try {
            $students = Student::query()
            ->join('registrations', 'students.id', '=', 'registrations.student_id')
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->selectRaw('
                courses.title AS course_title,
                students.gender,
                COUNT(*) AS student_count,
                CASE
                    WHEN DATE_PART(\'year\', AGE(students.data_of_birth)) < 15 THEN \'Alunos menores de 15 anos\'
                    WHEN DATE_PART(\'year\', AGE(students.data_of_birth)) BETWEEN 15 AND 18 THEN \'Alunos entre 15 e 18 anos\'
                    WHEN DATE_PART(\'year\', AGE(students.data_of_birth)) BETWEEN 19 AND 24 THEN \'Alunos entre 19 e 24 anos\'
                    WHEN DATE_PART(\'year\', AGE(students.data_of_birth)) BETWEEN 25 AND 30 THEN \'Alunos entre 25 e 30 anos\'
                    ELSE \'Alunos maiores de 30 anos\'
                END AS age_range
            ')
            ->groupBy('courses.title', 'students.gender', 'age_range')
            ->get();
        
        return $students;
           
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Ops, query failure.',
            ], 500);
        }
        
    }
}
