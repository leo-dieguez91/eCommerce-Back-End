<?php

namespace Tests\Feature\UserAddress;

use App\Models\User;
use App\Models\UserAddress;
use Tests\TestCase;

class UserAddressControllerTest extends TestCase
{
    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->accessToken;
    }

    public function test_can_create_user_address()
    {
        $addressData = [
            'street' => 'Calle de prueba',
            'city' => 'Test City',
            'state' => 'Test State',
            'zip_code' => '12345',
            'country' => 'Test Country',
            'type' => 'shipping'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/user/addresses', $addressData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'type',
                    'street',
                    'city',
                    'state',
                    'zip_code',
                    'country',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_can_get_all_user_addresses()
    {
        UserAddress::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/user/addresses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'type',
                        'street',
                        'city',
                        'state',
                        'zip_code',
                        'country',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_can_get_single_user_address()
    {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/user/addresses/{$address->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'type',
                    'street',
                    'city',
                    'state',
                    'zip_code',
                    'country',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_can_delete_user_address()
    {
        $address = UserAddress::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/user/addresses/{$address->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_addresses', [
            'id' => $address->id
        ]);
    }

    public function test_cannot_access_other_users_address()
    {
        $otherUser = User::factory()->create([
            'email' => 'other.user.' . time() . '@example.net'
        ]);

        $otherAddress = UserAddress::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/user/addresses/{$otherAddress->id}");

        $response->assertStatus(403);
    }

    public function test_cannot_create_invalid_address_type()
    {
        $addressData = [
            'street' => '123 Main St',
            'city' => 'Test City',
            'state' => 'Test State',
            'zip_code' => '12345',
            'country' => 'Test Country',
            'type' => 'invalid_type'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/user/addresses', $addressData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }
} 