<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_access(): void
    {
        $response = $this->getJson(uri: '/api/articles');

        $response->assertStatus(status: 401)
                 ->assertJson(value: ['error' => 'Unauthorized']);
    }

    public function test_authenticated_access(): void
    {
        $user = User::factory()->create();
        $token = auth(guard: 'api')->login(user: $user);

        $response = $this->withHeaders(headers: ['Authorization' => "Bearer {$token}"])
                         ->getJson(uri: '/api/articles');

        $response->assertStatus(status: 200);
    }
}
