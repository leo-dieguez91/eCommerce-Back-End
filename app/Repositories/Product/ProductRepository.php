<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll()
    {
        return $this->product->all();
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function update($product, array $data)
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
} 