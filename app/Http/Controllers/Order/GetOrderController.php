<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Endpoints para gestión de órdenes"
 * )
 */
class GetOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id?}",
     *     summary="Obtener orden(es)",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=false,
     *         description="ID de la orden (opcional)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="data", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="tracking_number", type="string"),
     *                         @OA\Property(property="status", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="datetime")
     *                     )
     *                 ),
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="data", type="array",
     *                         @OA\Items(ref="#/components/schemas/Order")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orden no encontrada"
     *     )
     * )
     */
    public function __invoke(Request $request, $id = null)
    {
        if ($id) {
            $order = $this->orderService->getOrderById($id);
            return new OrderResource($order);
        }

        return OrderResource::collection($this->orderService->getAllOrders());
    }

    /**
     * @OA\Get(
     *     path="/api/orders/tracking/{tracking_number}",
     *     summary="Obtener orden por número de tracking",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="tracking_number",
     *         in="path",
     *         required=true,
     *         description="Número de tracking de la orden",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orden no encontrada"
     *     )
     * )
     */
    public function getByTracking($tracking_number)
    {
        return $this->orderService->getOrderByTracking($tracking_number);
    }
}
