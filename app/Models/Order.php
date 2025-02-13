<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'shipping_address_id', 
        'billing_address_id', 
        'total_amount', 
        'status',
        'tracking_number'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'user_id' => 'integer',
        'shipping_address_id' => 'integer',
        'billing_address_id' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->tracking_number = strtoupper(Str::random(8));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
} 