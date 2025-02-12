<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $products = [
            'Laptop', 'Smartphone', 'Tablet', 'Headphones', 'Smartwatch',
            'Camera', 'Speaker', 'Monitor', 'Keyboard', 'Mouse',
            'Printer', 'Scanner', 'Router', 'Hard Drive', 'SSD',
            'RAM', 'Graphics Card', 'Processor', 'Motherboard', 'Power Supply'
        ];

        $productName = fake()->unique()->randomElement($products);

        return [
            'name' => $productName,
            'description' => "This is a {$productName} description",
            'price' => fake()->randomFloat(2, 99, 999),
            'stock' => fake()->numberBetween(12, 50),
        ];
    }
} 