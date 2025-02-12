<?php

namespace App\Services\Product;

use App\DTOs\Product\ProductDTO;
use App\Repositories\Product\ProductRepository;
use App\Traits\CacheHelper;

class ProductService
{
    use CacheHelper;

    private const CACHE_KEY = 'product';
    private const CACHE_TTL = 180;

    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->rememberCache(self::CACHE_KEY, null, self::CACHE_TTL, function () {
            return $this->productRepository->getAll();
        });
    }

    public function getProductById($id)
    {
        return $this->rememberCache(self::CACHE_KEY, $id, self::CACHE_TTL, function () use ($id) {
            return $this->productRepository->findById($id);
        });
    }

    public function createProduct(ProductDTO $productDTO)
    {
        $result = $this->productRepository->create($productDTO->toArray());
        
        $this->clearCache(self::CACHE_KEY);
        
        return $result;
    }

    public function updateProduct($id, ProductDTO $productDTO)
    {
        $product = $this->productRepository->findById($id);
        
        if (!$product) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Product not found");
        }
        
        $updateData = array_filter($productDTO->toArray(), function ($value) {
            return !is_null($value);
        });
        
        $result = $this->productRepository->update($product, $updateData);

        $this->clearCache(self::CACHE_KEY);
        $this->clearCache(self::CACHE_KEY, $id);
        
        return $result;
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->findById($id);
        $result = $this->productRepository->delete($product);

        $this->clearCache(self::CACHE_KEY);
        $this->clearCache(self::CACHE_KEY, $id);
        
        return $result;
    }
} 