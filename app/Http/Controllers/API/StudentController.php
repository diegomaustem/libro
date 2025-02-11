<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        if ($request->query('query') === 'filters') {
            $students = $this->getStudentsByFilters();
            return response()->json($students);
        } 

        $queryParams = $request->query();

        if (!empty($queryParams)) {
            $query = $this->organizeQueryParameters($queryParams);

            if(!is_null($query)) {
                try {
                    $students = Student::where($query['field'], 'ILIKE', '%' .$query['parameter']. '%')->get();
                    return StudentResource::collection($students);
                } catch (\Throwable $th) {
                    return response()->json([
                        'error' => 'Ops, falha na consulta.',
                    ], 500);
                }
            } else {
                return response()->json([
                    'error' => 'Parâmetro não encontrado.'
                ], 400);
            }
        }

        try {
            $students = Student::all();
            return StudentResource::collection($students);  
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Ops, falha na consulta.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Student $student)
    {
        $openStudentRegistration = $this->verifyOpenStudentRegistration($student->id);

        if (is_object($openStudentRegistration)) {
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
            ], 500);
        }
    }

    private function verifyOpenStudentRegistration($student)
    {
        $student = Registration::where('students_id', $student)->first();
        return $student;
    }

    private function organizeQueryParameters($queryParams)
    {
        $fieldsQuery = [];

        if(Arr::get($queryParams, 'name')) {
            $fieldsQuery['field'] = 'name';
            $fieldsQuery['parameter'] = $queryParams['name'];

            return $fieldsQuery;
        }

        if(Arr::get($queryParams, 'email')) {
            $fieldsQuery['field'] = 'email';
            $fieldsQuery['parameter'] = $queryParams['email'];
            
            return $fieldsQuery;
        }

        return null;
    }

    private function getStudentsByFilters()
    {
        try {
            $students = DB::table('students')
            ->join('registrations', 'students.id', '=', 'registrations.students_id')
            ->join('courses', 'registrations.courses_id', '=', 'courses.id')
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
                'error' => 'Ops, falha na consulta.',
            ], 500);
        }
        
    }
}
