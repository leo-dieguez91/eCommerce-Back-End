<?php

namespace App\Http\Controllers\UserAddress;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddress\UserAddressResource;
use App\Services\UserAddress\UserAddressService;
use Illuminate\Http\Request;

class GetUserAddressController extends Controller
{
    protected $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
    }

    public function __invoke(Request $request, $id = null)
    {
        if ($id) {
            $userAddress = $this->userAddressService->getUserAddressById($id);
            return new UserAddressResource($userAddress);
        }

        return UserAddressResource::collection($this->userAddressService->getAllUserAddresses());
    }
}
