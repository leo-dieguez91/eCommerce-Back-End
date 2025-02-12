<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    private $user;
    private $product;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->accessToken;
        $this->product = Product::factory()->create();
    }

    public function test_cannot_create_order_without_addresses()
    {
        $orderData = [
            'products' => [
                [
                    'id' => $this->product->id,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/orders', $orderData);

        $response->assertStatus(422)
                ->assertJsonPath('message', 'You need to add the following addresses before creating an order: shipping address, billing address')
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'products'
                    ]
                ]);
    }

    public function test_can_create_order_with_valid_addresses()
    {
        // Crear direcciones para el usuario
        $shippingAddress = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'shipping'
        ]);
        
        $billingAddress = UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'billing'
        ]);

        $orderData = [
            'products' => [
                [
                    'id' => $this->product->id,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'tracking_number',
                        'status',
                        'created_at'
                    ]
                ]);
    }

    public function test_cannot_create_order_with_invalid_product()
    {
        // Crear direcciones necesarias
        UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'shipping'
        ]);
        
        UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'billing'
        ]);

        $orderData = [
            'products' => [
                [
                    'id' => 99999, // ID que no existe
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/orders', $orderData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'products.0.id'
                    ]
                ])
                ->assertJsonPath('message', 'One of the products does not exist');
    }

    public function test_cannot_create_order_with_invalid_quantity()
    {
        // Crear direcciones necesarias
        UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'shipping'
        ]);
        
        UserAddress::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'billing'
        ]);

        $orderData = [
            'products' => [
                [
                    'id' => $this->product->id,
                    'quantity' => 0 // Cantidad inválida
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/orders', $orderData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'products.0.quantity'
                    ]
                ])
                ->assertJsonPath('message', 'The quantity must be greater than 0');
    }
} 