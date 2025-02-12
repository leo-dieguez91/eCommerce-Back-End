<?php

namespace App\DTOs\UserAddress;

class UserAddressDTO
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $state,
        public readonly string $country,
        public readonly string $zip_code,
        public readonly string $type,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            street: $request->street,
            city: $request->city,
            state: $request->state,
            country: $request->country,
            zip_code: $request->zip_code,
            type: $request->type,
        );
    }
} 