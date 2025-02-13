<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use App\Traits\CacheHelper;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderFailedNotification;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CacheHelper;

    private const CACHE_KEY = 'order';

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        DB::beginTransaction();
        try {
            $hasStock = true;
            $insufficientStockProducts = [];
            
            foreach ($this->order->products as $orderProduct) {
                $product = Product::findOrFail($orderProduct->product_id);
                if ($product->stock < $orderProduct->quantity) {
                    $hasStock = false;
                    $insufficientStockProducts[] = $product->name;
                }
            }

            if (!$hasStock) {
                $this->order->update(['status' => OrderStatus::CANCELLED]);
                DB::commit();
                throw new \Exception("Insufficient stock for products: " . implode(', ', $insufficientStockProducts));
            }

            foreach ($this->order->products as $orderProduct) {
                $product = Product::findOrFail($orderProduct->product_id);
                
                $product->decrement('stock', $orderProduct->quantity);
            }

            $this->order->update(['status' => OrderStatus::COMPLETED]);

            DB::commit();
            
            $this->order->user->notify(new OrderCreatedNotification($this->order));

            $this->clearCache(self::CACHE_KEY);
            $this->clearCache(self::CACHE_KEY, $this->order->id);
            $this->clearCache(self::CACHE_KEY, "tracking_{$this->order->tracking_number}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            if ($this->order->status !== OrderStatus::CANCELLED) {
                $this->order->update(['status' => OrderStatus::CANCELLED]);

                $this->order->user->notify(new OrderFailedNotification($this->order, $e->getMessage()));

                $this->clearCache(self::CACHE_KEY);
                $this->clearCache(self::CACHE_KEY, $this->order->id);
                $this->clearCache(self::CACHE_KEY, "tracking_{$this->order->tracking_number}");
            }
            throw $e;
        }
    }
} 