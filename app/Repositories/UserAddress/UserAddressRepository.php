<?php

namespace App\Repositories\UserAddress;

use App\Models\UserAddress;

class UserAddressRepository
{
    protected $userAddress;

    public function __construct(UserAddress $userAddress)
    {
        $this->userAddress = $userAddress;
    }

    public function getAll()
    {
        return $this->userAddress->all();
    }

    public function findById($id)
    {
        return $this->userAddress->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->userAddress->create($data);
    }

    public function update(UserAddress $userAddress, array $data)
    {
        return $userAddress->update($data);
    }

    public function delete(UserAddress $userAddress)
    {
        return $userAddress->delete();
    }
} 