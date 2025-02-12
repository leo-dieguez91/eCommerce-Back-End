<?php

namespace App\Policies\UserAddress;

use App\Models\User;
use App\Models\UserAddress;
use App\Enums\AddressType;
use Illuminate\Auth\Access\AuthorizationException;

class UserAddressPolicy
{
    public function create(User $user, string $type)
    {
        if (!in_array($type, AddressType::all())) {
            throw new AuthorizationException('Invalid address type. Must be either "shipping" or "billing".');
        }

        return true;
    }

    public function view(User $user, UserAddress $userAddress)
    {
        return $user->id === $userAddress->user_id;
    }

    public function delete(User $user, UserAddress $userAddress)
    {
        return $user->id === $userAddress->user_id;
    }
}