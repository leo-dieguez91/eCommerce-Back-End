<?php

namespace App\Http\Controllers\Product;

use App\DTOs\Product\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Services\Product\ProductService;

class PostProductController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear nuevo producto",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "stock"},
     *             @OA\Property(property="name", type="string", example="Nuevo Producto"),
     *             @OA\Property(property="description", type="string", example="Descripción del producto"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99),
     *             @OA\Property(property="stock", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function __invoke(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        
        $productDTO = new ProductDTO(
            name: $validatedData['name'],
            description: $validatedData['description'],
            price: $validatedData['price'],
            stock: $validatedData['stock']
        );
        $product = $this->productService->createProduct($productDTO);
        return new ProductResource($product);
    }
}
