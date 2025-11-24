<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_type' => ['nullable', 'in:amount,percentage'],
            'discount_amount' => ['nullable', 'integer', 'min:0'],
            'discount_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'need_preparation_time' => ['required', 'boolean'],
            'preparation_time' => ['nullable', 'integer', 'min:0'],
            'has_order_limit' => ['required', 'boolean'],
            'order_limit' => ['nullable', 'integer', 'min:1'],
            'is_default' => ['required', 'boolean'],
            'status' => ['nullable', 'in:draft,active,inactive'],
            'variant_values' => ['nullable', 'array'],
            'media_ids' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'محصول مربوطه الزامی است.',
            'product_id.exists' => 'محصول انتخاب شده معتبر نیست.',
            'price.required' => 'قیمت محصول الزامی است.',
            'price.integer' => 'قیمت باید عدد صحیح باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',
            'discount_type.in' => 'نوع تخفیف معتبر نیست.',
            'discount_amount.integer' => 'مقدار تخفیف باید عدد صحیح باشد.',
            'discount_amount.min' => 'مقدار تخفیف نمی‌تواند منفی باشد.',
            'discount_percentage.integer' => 'درصد تخفیف باید عدد صحیح باشد.',
            'discount_percentage.min' => 'درصد تخفیف نمی‌تواند منفی باشد.',
            'discount_percentage.max' => 'درصد تخفیف نمی‌تواند بیش از 100 باشد.',
            'need_preparation_time.required' => 'فیلد نیاز به زمان آماده‌سازی الزامی است.',
            'need_preparation_time.boolean' => 'مقدار نیاز به زمان آماده‌سازی باید درست یا غلط باشد.',
            'preparation_time.integer' => 'زمان آماده‌سازی باید عدد صحیح باشد.',
            'preparation_time.min' => 'زمان آماده‌سازی نمی‌تواند منفی باشد.',
            'has_order_limit.required' => 'فیلد محدودیت سفارش الزامی است.',
            'has_order_limit.boolean' => 'مقدار محدودیت سفارش باید درست یا غلط باشد.',
            'order_limit.integer' => 'حد سفارش باید عدد صحیح باشد.',
            'order_limit.min' => 'حد سفارش باید حداقل 1 باشد.',
            'is_default.required' => 'فیلد پیش‌فرض بودن الزامی است.',
            'is_default.boolean' => 'مقدار پیش‌فرض بودن باید درست یا غلط باشد.',
            'status.in' => 'وضعیت واردشده معتبر نیست.',
        ];
    }
}
