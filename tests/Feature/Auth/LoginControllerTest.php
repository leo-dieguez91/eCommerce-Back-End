<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Services\Auth\AuthService;
use Mockery;

class LoginControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = Mockery::mock(AuthService::class);
        $this->app->instance(AuthService::class, $this->authService);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create();
        $credentials = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $expectedResponse = [
            'data' => [
                'access_token' => 'fake-token',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]
        ];

        $this->mock(AuthService::class)
            ->shouldReceive('login')
            ->once()
            ->with($credentials)
            ->andReturn($expectedResponse);

        $response = $this->postJson('/api/auth/login', $credentials);

        $response->assertStatus(200)
                ->assertJson($expectedResponse);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $credentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ];

        $this->authService
            ->shouldReceive('login')
            ->once()
            ->with($credentials)
            ->andThrow(new \Illuminate\Auth\AuthenticationException('Invalid credentials'));

        $response = $this->postJson('/api/auth/login', $credentials);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Invalid credentials'
                ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
} 