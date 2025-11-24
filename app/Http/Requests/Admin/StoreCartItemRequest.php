<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'cart_id.required' => 'شناسه سبد خرید الزامی است.',
            'cart_id.exists' => 'سبد خرید انتخاب‌شده معتبر نیست.',
            'product_id.exists' => 'محصول انتخاب‌شده معتبر نیست.',
            'product_variant_id.exists' => 'واریانت انتخاب‌شده معتبر نیست.',
            'quantity.required' => 'تعداد باید وارد شود.',
            'quantity.integer' => 'تعداد باید عددی باشد.',
            'quantity.min' => 'تعداد نمی‌تواند کمتر از ۱ باشد.',
            'unit_price.required' => 'قیمت واحد الزامی است.',
            'unit_price.integer' => 'قیمت واحد باید عددی باشد.',
            'unit_price.min' => 'قیمت واحد نمی‌تواند منفی باشد.',
        ];
    }
}
