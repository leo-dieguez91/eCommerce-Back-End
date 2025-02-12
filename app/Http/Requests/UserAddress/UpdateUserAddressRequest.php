<?php

namespace App\Http\Requests\UserAddress;

use App\Enums\AddressType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'zip_code' => 'required|string',
            'type' => 'required|in:' . implode(',', AddressType::all()),
        ];
    }
} 