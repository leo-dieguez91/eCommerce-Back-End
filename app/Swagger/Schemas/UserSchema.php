<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User Schema",
 *     description="Esquema que representa un usuario en el sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="datetime", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2024-03-20 10:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2024-03-20 10:00:00")
 * )
 */
class UserSchema
{
    // Esta clase puede estar vacía, solo se usa para la documentación
} 