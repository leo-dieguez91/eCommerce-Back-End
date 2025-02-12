<?php

namespace App\Repositories\Order;

use App\Models\Order;

class OrderRepository
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getAll()
    {
        return $this->order->all();
    }

    public function findById($id)
    {
        return $this->order->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function update(Order $order, array $data)
    {
        return $order->update($data);
    }

    public function delete(Order $order)
    {
        return $order->delete();
    }

    public function findByTracking($trackingNumber)
    {
        return Order::where('tracking_number', $trackingNumber)->firstOrFail();
    }
} 