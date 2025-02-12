<?php

namespace App\Http\Controllers\UserAddress;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddress\CreateUserAddressRequest;
use App\Services\UserAddress\UserAddressService;
use App\DTOs\UserAddress\UserAddressDTO;

class PostUserAddressController extends Controller
{
    public function __construct(
        private UserAddressService $userAddressService
    ) {}

    public function __invoke(CreateUserAddressRequest $request)
    {
        $addressDTO = UserAddressDTO::fromRequest($request);
        return $this->userAddressService->createOrUpdate($addressDTO);
    }
}
