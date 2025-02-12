<?php

namespace App\Services\Order;

use App\DTOs\Order\OrderDTO;
use App\Repositories\Order\OrderRepository;
use App\Jobs\ProcessOrderJob;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Traits\CacheHelper;

class OrderService
{
    use CacheHelper;

    private const CACHE_KEY = 'order';
    private const CACHE_TTL = 180;
    
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders()
    {
        return $this->rememberCache(self::CACHE_KEY, null, self::CACHE_TTL, function () {
            if (Gate::allows('viewAny', Order::class)) {
                return Order::with(['products', 'user'])
                    ->select(['id', 'user_id', 'total_amount', 'status', 'tracking_number', 'shipping_address_id', 'billing_address_id', 'created_at', 'updated_at'])
                    ->latest()
                    ->get();
            }
            
            return Order::with('products')
                ->select(['id', 'user_id', 'total_amount', 'status', 'tracking_number', 'shipping_address_id', 'billing_address_id', 'created_at', 'updated_at'])
                ->where('user_id', auth('api')->id())
                ->latest()
                ->get();
        });
    }

    public function getOrderById($id)
    {
        return $this->rememberCache(self::CACHE_KEY, $id, self::CACHE_TTL, function () use ($id) {
            $order = Order::findOrFail($id);
            
            $response = Gate::inspect('view', $order);
            if (!$response->allowed()) {
                throw new AuthorizationException($response->message());
            }
            
            return $order;
        });
    }

    public function createOrder(OrderDTO $orderDTO)
    {
        $response = Gate::inspect('create', [Order::class, $orderDTO->products]);
        if (!$response->allowed()) {
            throw new AuthorizationException($response->message());
        }

        $totalAmount = 0;
        foreach ($orderDTO->products as $product) {
            $productModel = Product::findOrFail($product['id']);
            $totalAmount += $productModel->price * $product['quantity'];
        }

        $order = Order::create([
            'user_id' => $orderDTO->userId,
            'shipping_address_id' => $orderDTO->shipping_address_id,
            'billing_address_id' => $orderDTO->billing_address_id,
            'total_amount' => $totalAmount,
            'status' => $orderDTO->status
        ]);

        foreach ($orderDTO->products as $product) {
            $order->products()->create([
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => Product::find($product['id'])->price
            ]);
        }

        ProcessOrderJob::dispatch($order);

        $this->clearCache(self::CACHE_KEY);

        return $order;
    }

    public function updateOrder($id, OrderDTO $orderDTO)
    {
        $order = $this->orderRepository->findById($id);
        
        $allowedUpdates = array_intersect_key(
            $orderDTO->toArray(),
            array_flip(['status', 'shipping_address_id', 'billing_address_id'])
        );
        
        $result = $this->orderRepository->update($order, $allowedUpdates);

        $this->clearCache(self::CACHE_KEY);
        $this->clearCache(self::CACHE_KEY, $id);

        return $result;
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        
        if (!auth('api')->user()->isAdmin()) {
            throw new AuthorizationException('You do not have permission to delete orders.');
        }
        
        $result = $order->delete();

        // Limpiar cache después de eliminar
        $this->clearCache(self::CACHE_KEY);
        $this->clearCache(self::CACHE_KEY, $id);

        return $result;
    }

    public function getOrderByTracking($trackingNumber)
    {
        return $this->rememberCache(self::CACHE_KEY, "tracking_{$trackingNumber}", self::CACHE_TTL, function () use ($trackingNumber) {
            $order = $this->orderRepository->findByTracking($trackingNumber);
            
            return [
                'tracking_number' => $order->tracking_number,
                'status' => $order->status,
                'created_at' => $order->created_at,
            ];
        });
    }
} 