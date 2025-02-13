<?php

namespace App\Http\Requests\Product;

use App\DTOs\Product\ProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ];
    }

    public function toDTO(): ProductDTO
    {
        return new ProductDTO(
            name: $this->input('name'),
            description: $this->input('description'),
            price: $this->input('price'),
            stock: $this->input('stock'),
        );
    }
} 