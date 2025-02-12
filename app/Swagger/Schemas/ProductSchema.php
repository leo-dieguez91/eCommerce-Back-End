<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product Schema",
 *     description="Esquema que representa un producto en el sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Producto de ejemplo"),
 *     @OA\Property(property="description", type="string", example="Descripción del producto"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="stock", type="integer", example=100),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 */
class ProductSchema
{
} 