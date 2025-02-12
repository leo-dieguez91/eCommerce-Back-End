<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'street' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'type' => $this->faker->randomElement(['shipping', 'billing'])
        ];
    }

    /**
     * Indicate that the address is for shipping.
     */
    public function shipping()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'shipping',
            ];
        });
    }

    /**
     * Indicate that the address is for billing.
     */
    public function billing()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'billing',
            ];
        });
    }
} 