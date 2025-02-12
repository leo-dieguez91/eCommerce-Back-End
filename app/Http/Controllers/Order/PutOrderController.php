<?php

namespace App\Http\Controllers\Order;

use App\DTOs\Order\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderService;

/**
 * @OA\Put(
 *     path="/api/orders/{id}",
 *     summary="Actualizar orden existente",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la orden",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string"),
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
 *         response=200,
 *         description="Orden actualizada exitosamente",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Orden no encontrada"
 *     )
 * )
 */
class PutOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(UpdateOrderRequest $request, $id)
    {
        $orderDTO = new OrderDTO($request->validated());
        $order = $this->orderService->updateOrder($id, $orderDTO);
        return new OrderResource($order);
    }
}
