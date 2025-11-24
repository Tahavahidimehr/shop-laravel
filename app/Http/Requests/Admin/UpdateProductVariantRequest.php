<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'exists:products,id'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'discount_type' => ['sometimes', 'in:amount,percentage'],
            'discount_amount' => ['sometimes', 'integer', 'min:0'],
            'discount_percentage' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'need_preparation_time' => ['sometimes', 'boolean'],
            'preparation_time' => ['sometimes', 'integer', 'min:0'],
            'has_order_limit' => ['sometimes', 'boolean'],
            'order_limit' => ['sometimes', 'integer', 'min:1'],
            'is_default' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'in:draft,active,inactive'],
            'variant_values' => ['nullable', 'array'],
            'media_ids' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return (new StoreProductVariantRequest())->messages();
    }
}
