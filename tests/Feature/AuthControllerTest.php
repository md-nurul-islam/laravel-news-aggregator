<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login(): void
    {
        $user = User::factory()->create(attributes: [
            'email' => 'test@example.com',
            'password' => bcrypt(value: 'password'),
        ]);

        $response = $this->postJson(uri: '/api/login', data: [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(status: 200)
                 ->assertJsonStructure(structure: ['token']);
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();
        $token = auth(guard: 'api')->login(user: $user);

        $response = $this->withHeaders(headers: ['Authorization' => "Bearer {$token}"])
                         ->postJson(uri: '/api/logout');

        $response->assertStatus(status: 200)
                 ->assertJson(value: ['message' => 'Successfully logged out']);
    }

    public function test_refresh_token(): void
    {
        $user = User::factory()->create();
        $token = auth(guard: 'api')->login(user: $user);

        $response = $this->withHeaders(headers: ['Authorization' => "Bearer {$token}"])
                         ->postJson(uri: '/api/refresh');

        $response->assertStatus(status: 200)
                 ->assertJsonStructure(structure: ['token']);
    }
}
