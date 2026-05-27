<?php

namespace Tests\Feature;

use App\Models\DemoRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_demo_request(): void
    {
        $response = $this->post('/demo-request', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'organization' => 'ACME Corp',
            'phone' => '+1234567890',
            'message' => 'Hello, I want a demo please!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('demo_success', true);

        $this->assertDatabaseHas('demo_requests', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'organization' => 'ACME Corp',
            'phone' => '+1234567890',
            'message' => 'Hello, I want a demo please!',
        ]);
    }

    public function test_validation_errors_for_invalid_demo_request(): void
    {
        $response = $this->post('/demo-request', [
            'name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
        $this->assertDatabaseCount('demo_requests', 0);
    }
}
