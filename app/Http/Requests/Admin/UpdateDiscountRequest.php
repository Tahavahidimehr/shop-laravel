<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $discountId = $this->route('discount')?->id ?? null;

        return [
            'code' => 'sometimes|string|unique:discounts,code,' . $discountId,
            'type' => 'sometimes|in:amount,percentage',
            'amount' => 'nullable|integer|min:0',
            'percentage' => 'nullable|integer|min:0|max:100',
            'max_discount_price' => 'nullable|integer|min:0',
            'min_purchase_price' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'کد تخفیف تکراری است.',
            'type.in' => 'نوع تخفیف باید یکی از مقادیر amount یا percentage باشد.',
            'percentage.max' => 'درصد تخفیف نمی‌تواند بیشتر از ۱۰۰ باشد.',
            'expires_at.after_or_equal' => 'تاریخ انقضا باید بعد از تاریخ شروع باشد.',
            'user_id.exists' => 'کاربر انتخاب‌شده معتبر نیست.',
        ];
    }
}
