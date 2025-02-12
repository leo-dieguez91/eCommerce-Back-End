<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        
        Passport::actingAs($user);
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out'
            ]);
    }

    public function test_unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }
} 