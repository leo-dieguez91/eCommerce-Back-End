<?php

namespace App\Services\UserAddress;

use App\DTOs\UserAddress\UserAddressDTO;
use App\Models\UserAddress;
use App\Traits\CacheHelper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\UserAddress\UserAddressResource;

class UserAddressService
{
    use CacheHelper;

    protected $model;

    public function __construct()
    {
        $this->model = UserAddress::class;
    }

    private const CACHE_KEY = 'user_address';
    private const CACHE_TTL = null; 
    
    public function getAllUserAddresses()
    {
        return $this->rememberCache(self::CACHE_KEY, null, self::CACHE_TTL, function () {
            return UserAddress::where('user_id', auth('api')->id())->get();
        });
    }

    public function getUserAddressById($id)
    {
        return $this->rememberCache(self::CACHE_KEY, $id, self::CACHE_TTL, function () use ($id) {
            $address = UserAddress::findOrFail($id);
            
            if ($address->user_id !== auth('api')->id()) {
                throw new AuthorizationException('You do not have permission to access this address.');
            }
    
            return $address;
        });
    }

    public function createOrUpdate(UserAddressDTO $addressDTO)
    {
        $response = Gate::inspect('create', [UserAddress::class, $addressDTO->type]);
        
        if (!$response->allowed()) {
            throw new AuthorizationException($response->message());
        }

        $result = UserAddress::updateOrCreate(
            [
                'user_id' => auth('api')->id(),
                'type' => $addressDTO->type
            ],
            [
                'user_id' => auth('api')->id(),
                'type' => $addressDTO->type,
                'street' => $addressDTO->street,
                'city' => $addressDTO->city,
                'state' => $addressDTO->state,
                'country' => $addressDTO->country,
                'zip_code' => $addressDTO->zip_code
            ]
        );

        // Limpiar cache después de crear/actualizar
        $this->clearCache(self::CACHE_KEY);
        $this->clearCache(self::CACHE_KEY, $result->id);

        return new UserAddressResource($result);
    }

    public function deleteUserAddress($id): void
    {
        $address = UserAddress::findOrFail($id);
        
        if (!Gate::allows('delete', $address)) {
            throw new AuthorizationException('You do not have permission to delete this address.');
        }

        $address->delete();

        // Limpiar cache después de eliminar
        $this->clearCache(self::CACHE_KEY);
        $this->clearCache(self::CACHE_KEY, $id);
    }
} 