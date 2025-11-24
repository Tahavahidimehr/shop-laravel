<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'name' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'type' => [ 'sometimes', Rule::in(['simple', 'variable']) ],
            'price' => 'sometimes|nullable|integer|min:0',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'discount_type' => ['sometimes', Rule::in(['amount', 'percentage'])],
            'discount_amount' => 'sometimes|nullable|integer|min:0',
            'discount_percentage' => 'sometimes|nullable|integer|min:0|max:100',
            'special_offer' => 'sometimes|boolean',
            'need_preparation_time' => 'sometimes|boolean',
            'preparation_time' => 'sometimes|nullable|integer|min:0',
            'weight' => 'sometimes|nullable|integer|min:0',
            'weight_unit' => ['sometimes', Rule::in(['g', 'kg'])],
            'description' => 'sometimes|nullable|string',
            'has_order_limit' => 'sometimes|boolean',
            'order_limit' => 'sometimes|nullable|integer|min:1',
            'status' => ['sometimes', Rule::in(['draft', 'active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'وارد کردن نام محصول الزامی است.',
            'slug.unique' => 'این نامک (slug) قبلاً استفاده شده است.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
            'price.integer' => 'قیمت باید یک عدد صحیح باشد.',
            'weight.integer' => 'وزن باید یک عدد صحیح باشد.',
            'order_limit.integer' => 'حد سفارش باید عدد صحیح باشد.',
            'discount_percentage.max' => 'درصد تخفیف نمی‌تواند بیش از ۱۰۰ باشد.',
        ];
    }
}
