<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\Product\ProductService;
use App\DTOs\Product\ProductDTO;
use Illuminate\Http\Request;

/**
 * @OA\Put(
 *     path="/api/products/{id}",
 *     summary="Actualizar producto existente",
 *     tags={"Products"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del producto",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="price", type="number", format="float"),
 *             @OA\Property(property="stock", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Producto actualizado exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", ref="#/components/schemas/Product")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Producto no encontrado"
 *     )
 * )
 */
class PutProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function __invoke(Request $request, $id)
    {
        $request->merge($request->all());
        $productDTO = ProductDTO::fromRequest($request);
        return $this->productService->updateProduct($id, $productDTO);
    }
}
