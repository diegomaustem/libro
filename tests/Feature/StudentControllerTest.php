<?php

namespace Tests\Feature;

use App\Http\Resources\StudentResource;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowReturnsStudent()
    {
        $student = Student::factory()->create();

        $response = $this->getJson("/libro/students/{$student->id}");
        $response->assertStatus(200);

        $response->assertJson(
            (new StudentResource($student))->response()->getData(true)
        );
    }

    public function testStoreCreatesStudentSuccessfully()
    {
        $data = [
            'name' => 'João Silva',
            'email' => 'joao.silva@example.com',
            'gender' => 'M',
            'data_of_birth' => '2000-01-02',
        ];

        $response = $this->postJson('/libro/students', $data);
        $response->assertStatus(201);

        $response->assertJson([
            'mensagem' => 'Student inserted.',
        ]);

        //   Checks if the student was created in the database
        $this->assertDatabaseHas('students', $data);

        //   Checks if the response contains the created student
        $student = Student::first();
        $response->assertJson([
            'student' => (new StudentResource($student))->jsonSerialize(),
        ]);
    }

    public function testDestroyConflictWhenStudentHasOpenRegistration()
    {
        $student = Student::factory()->create();
        $course  = Course::factory()->create();

        $registration = Registration::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id, 
        ]);

        $response = $this->deleteJson("/libro/students/{$student->id}");
        $response->assertStatus(409);

        // Checks whether the response contains the conflict message
        $response->assertJson([
            'error' => 'Conflict.',
            'message' => 'The student is open enrollment. It is necessary to close the registration for deletion.',
        ]);
    }
}

