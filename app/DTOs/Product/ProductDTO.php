<?php

namespace App\DTOs\Product;

class ProductDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stock = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
        ], function ($value) {
            return !is_null($value);
        });
    }
} 