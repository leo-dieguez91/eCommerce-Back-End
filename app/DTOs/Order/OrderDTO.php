<?php

namespace App\DTOs\Order;

use App\Enums\AddressType;

class OrderDTO
{
    public $userId;
    public $totalAmount;
    public $status;
    public $products;
    public $shipping_address_id;
    public $billing_address_id;

    public function __construct(array $data)
    {
        $user = auth()->user();
        
        $this->userId = $user->id;
        
        // Obtener la última dirección de envío
        $shippingAddress = $user->addresses()
            ->where('type', AddressType::SHIPPING)
            ->latest()
            ->first();
            
        // Obtener la última dirección de facturación
        $billingAddress = $user->addresses()
            ->where('type', AddressType::BILLING)
            ->latest()
            ->first();
        
        $this->shipping_address_id = $shippingAddress->id;
        $this->billing_address_id = $billingAddress->id;
        $this->products = $data['products'];
        $this->status = 'pending';
        $this->totalAmount = null; // Se calculará en el servicio
    }

    public function toArray()
    {
        return [
            'user_id' => $this->userId,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'shipping_address_id' => $this->shipping_address_id,
            'billing_address_id' => $this->billing_address_id
        ];
    }
}