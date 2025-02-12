<?php

namespace App\Rules;

use App\Enums\AddressType;
use Illuminate\Contracts\Validation\Rule;

class HasRequiredAddresses implements Rule
{
    protected $missingAddresses = [];

    public function passes($attribute, $value): bool
    {
        $user = auth()->user();

        // Check shipping address
        if (!$user->addresses()->where('type', AddressType::SHIPPING)->exists()) {
            $this->missingAddresses[] = 'shipping address';
        }

        // Check billing address
        if (!$user->addresses()->where('type', AddressType::BILLING)->exists()) {
            $this->missingAddresses[] = 'billing address';
        }

        return empty($this->missingAddresses);
    }

    public function message(): string
    {
        return 'You need to add the following addresses before creating an order: ' . 
               implode(', ', $this->missingAddresses);
    }
} 