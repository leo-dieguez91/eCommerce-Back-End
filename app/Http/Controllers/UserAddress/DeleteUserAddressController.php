<?php

namespace App\Http\Controllers\UserAddress;

use App\Http\Controllers\Controller;
use App\Http\Responses\DeleteResponse;
use App\Services\UserAddress\UserAddressService;

class DeleteUserAddressController extends Controller
{
    protected $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
    }

    public function __invoke($id)
    {
        $this->userAddressService->deleteUserAddress($id);
        return new DeleteResponse();
    }
}
