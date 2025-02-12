<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order Schema",
 *     description="Esquema que representa una orden en el sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="tracking_number", type="string", example="ORD-2024-001"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="products", type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="quantity", type="integer", example=2),
 *             @OA\Property(property="price", type="number", format="float", example=29.99)
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2024-03-20 10:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2024-03-20 10:00:00")
 * )
 */
class OrderSchema
{
    // Esta clase puede estar vacía, solo se usa para la documentación
} 