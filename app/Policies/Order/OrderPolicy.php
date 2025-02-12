<?php

namespace App\Policies\Order;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    use HandlesAuthorization;

    public function create(User $user, array $productos): Response
    {
        if (empty($productos)) {
            return Response::deny('The order must contain at least one product');
        }

        foreach ($productos as $producto) {
            if (!Product::find($producto['id'])) {
                return Response::deny("Product with ID {$producto['id']} does not exist");
            }
        }

        return Response::allow();
    }

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Order $order)
    {
        return $user->role === 'admin' || $order->user_id === $user->id;
    }

    public function delete(User $user, Order $order)
    {
        return $user->role === 'admin';
    }
} 