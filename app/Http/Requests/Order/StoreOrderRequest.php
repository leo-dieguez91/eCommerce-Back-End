<?php

namespace App\Http\Requests\Order;

use App\Rules\HasRequiredAddresses;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => ['required', 'array', 'min:1', new HasRequiredAddresses],
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'products.required' => 'You must include at least one product',
            'products.array' => 'The products format is invalid',
            'products.min' => 'You must include at least one product',
            'products.*.id.required' => 'The product ID is required',
            'products.*.id.exists' => 'One of the products does not exist',
            'products.*.quantity.required' => 'The quantity is required',
            'products.*.quantity.integer' => 'The quantity must be an integer',
            'products.*.quantity.min' => 'The quantity must be greater than 0'
        ];
    }
} 