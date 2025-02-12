<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;

class TrackingController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function __invoke($trackingNumber)
    {
        $order = $this->orderService->getOrderByTracking($trackingNumber);
        
        return view('tracking.show', [
            'order' => $order
        ]);
    }
} 