<?php

namespace App\Http\Controllers\Order;

use App\DTOs\Order\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderService;

/**
 * @OA\Post(
 *     path="/api/orders",
 *     summary="Crear nueva orden",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "products"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="products", type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="product_id", type="integer"),
 *                     @OA\Property(property="quantity", type="integer")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Orden creada exitosamente",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación"
 *     )
 * )
 */
class PostOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(StoreOrderRequest $request)
    {
        $orderDTO = new OrderDTO($request->validated());
        $order = $this->orderService->createOrder($orderDTO);
        return new OrderResource($order);
    }
}
