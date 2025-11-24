<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'type' => ['required', Rule::in(['simple', 'variable'])],
            'price' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'discount_type' => ['nullable', Rule::in(['amount', 'percentage'])],
            'discount_amount' => 'nullable|integer|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'special_offer' => 'boolean',
            'need_preparation_time' => 'boolean',
            'preparation_time' => 'nullable|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'weight_unit' => ['nullable', Rule::in(['g', 'kg'])],
            'description' => 'nullable|string',
            'has_order_limit' => 'boolean',
            'order_limit' => 'nullable|integer|min:1',
            'status' => ['nullable', Rule::in(['draft', 'active', 'inactive'])],
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
