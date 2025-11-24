<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountUserUsageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'discount_id' => ['required', 'exists:discounts,id'],
            'user_id' => ['required', 'exists:users,id'],
            'used_count' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'discount_id.required' => 'شناسه تخفیف الزامی است.',
            'discount_id.exists' => 'تخفیف انتخاب شده معتبر نیست.',
            'user_id.required' => 'شناسه کاربر الزامی است.',
            'user_id.exists' => 'کاربر انتخاب شده معتبر نیست.',
            'used_count.integer' => 'تعداد استفاده باید یک عدد صحیح باشد.',
            'used_count.min' => 'تعداد استفاده نمی‌تواند منفی باشد.',
        ];
    }
}
