<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'address_id' => 'required|exists:addresses,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'shipping_method' => 'required|string|max:255',
            'discount_code' => 'nullable|string|max:50',
            'status' => 'in:pending,paid,processing,shipped,delivered,cancelled,failed',
            'total_price' => 'required|integer|min:0',
            'discount_code_price' => 'nullable|integer|min:0',
            'total_product_discount_price' => 'nullable|integer|min:0',
            'tax_price' => 'nullable|integer|min:0',
            'shipping_price' => 'nullable|integer|min:0',
            'packing_price' => 'nullable|integer|min:0',
            'price_to_pay' => 'required|integer|min:0',
            'tracking_code' => 'nullable|string|max:255',
            'shipped_at' => 'nullable|date',
            'paid_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'کاربر الزامی است.',
            'user_id.exists' => 'کاربر انتخاب‌شده معتبر نیست.',
            'address_id.required' => 'آدرس الزامی است.',
            'address_id.exists' => 'آدرس انتخاب‌شده معتبر نیست.',
            'shipping_method_id.required' => 'روش ارسال الزامی است.',
            'shipping_method_id.exists' => 'روش ارسال انتخاب‌شده معتبر نیست.',
            'payment_method_id.required' => 'روش پرداخت الزامی است.',
            'payment_method_id.exists' => 'روش پرداخت انتخاب‌شده معتبر نیست.',
            'name.required' => 'نام مشتری الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
            'address.required' => 'آدرس الزامی است.',
            'postal_code.required' => 'کد پستی الزامی است.',
            'province.required' => 'استان الزامی است.',
            'city.required' => 'شهر الزامی است.',
            'total_price.required' => 'مبلغ کل الزامی است.',
            'price_to_pay.required' => 'مبلغ قابل پرداخت الزامی است.',
            'status.in' => 'وضعیت سفارش معتبر نیست.',
        ];
    }
}
