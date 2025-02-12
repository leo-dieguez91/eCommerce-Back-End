<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Responses\DeleteResponse;
use App\Services\Order\OrderService;

/**
 * @OA\Delete(
 *     path="/api/orders/{id}",
 *     summary="Eliminar orden",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la orden a eliminar",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Orden eliminada exitosamente"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Orden no encontrada"
 *     )
 * )
 */
class DeleteOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke($id)
    {
        $this->orderService->deleteOrder($id);
        return new DeleteResponse();
    }
}
