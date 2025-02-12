<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroy_deletes_registration()
    {
        $registration = Registration::factory()->create();

        $response = $this->deleteJson("/libro/registrations/{$registration->id}");
        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Excluded registration.',
        ]);
        
        $this->assertDatabaseMissing('registrations', ['id' => $registration->id]);
    }
 

}
