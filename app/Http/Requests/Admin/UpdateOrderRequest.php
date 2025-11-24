<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $orderId = $this->route('order')?->id ?? null;

        return [
            'user_id' => 'sometimes|exists:users,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'address_id' => 'sometimes|exists:addresses,id',
            'shipping_method_id' => 'sometimes|exists:shipping_methods,id',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'postal_code' => 'sometimes|string|max:20',
            'province' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'payment_method' => 'sometimes|string|max:255',
            'shipping_method' => 'sometimes|string|max:255',
            'discount_code' => 'nullable|string|max:50',
            'status' => 'sometimes|in:pending,paid,processing,shipped,delivered,cancelled,failed',
            'total_price' => 'sometimes|integer|min:0',
            'discount_code_price' => 'nullable|integer|min:0',
            'total_product_discount_price' => 'nullable|integer|min:0',
            'tax_price' => 'nullable|integer|min:0',
            'shipping_price' => 'nullable|integer|min:0',
            'packing_price' => 'nullable|integer|min:0',
            'price_to_pay' => 'sometimes|integer|min:0',
            'tracking_code' => 'nullable|string|max:255',
            'shipped_at' => 'nullable|date',
            'paid_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'کاربر انتخاب‌شده معتبر نیست.',
            'address_id.exists' => 'آدرس انتخاب‌شده معتبر نیست.',
            'shipping_method_id.exists' => 'روش ارسال انتخاب‌شده معتبر نیست.',
            'payment_method_id.exists' => 'روش پرداخت انتخاب‌شده معتبر نیست.',
            'status.in' => 'وضعیت سفارش معتبر نیست.',
        ];
    }
}
