<?php

namespace Tests\Feature;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    // Test whether a list of courses is returned
    public function testReturnsListOfCoursesWhenQueried()
    {
        $courses = Course::factory()->count(3)->create();

        $response = $this->get('/libro/courses');
        $response->assertStatus(200);

        // Check if the response contains the created courses
        $response->assertJson(
            CourseResource::collection($courses)->response()->getData(true)
        );
    }

    public function testChecksIfCourseHasBeenDeleted()
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson("/libro/courses/{$course->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Excluded course.',
        ]);

        // Checks if the course has been deleted from the database
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}
