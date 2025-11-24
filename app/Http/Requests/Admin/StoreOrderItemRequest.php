<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0',
            'price_to_pay' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'شناسه سفارش لازم است.',
            'order_id.exists' => 'سفارشی با این شناسه وجود ندارد.',
            'product_id.required' => 'محصول لازم است.',
            'product_id.exists' => 'محصول انتخاب شده معتبر نیست.',
            'product_variant_id.exists' => 'متغیر محصول انتخاب شده معتبر نیست.',
            'quantity.required' => 'تعداد محصول لازم است.',
            'quantity.integer' => 'تعداد باید یک عدد صحیح باشد.',
            'quantity.min' => 'تعداد باید حداقل 1 باشد.',
            'unit_price.required' => 'قیمت واحد لازم است.',
            'unit_price.integer' => 'قیمت واحد باید عدد باشد.',
            'unit_price.min' => 'قیمت واحد نمی‌تواند منفی باشد.',
            'discount_price.integer' => 'قیمت تخفیف باید عدد باشد.',
            'discount_price.min' => 'قیمت تخفیف نمی‌تواند منفی باشد.',
            'price_to_pay.required' => 'مبلغ قابل پرداخت لازم است.',
            'price_to_pay.integer' => 'مبلغ قابل پرداخت باید عدد باشد.',
            'price_to_pay.min' => 'مبلغ قابل پرداخت نمی‌تواند منفی باشد.',
        ];
    }
}
