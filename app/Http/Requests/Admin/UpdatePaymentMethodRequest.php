<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentMethodId = $this->route('payment_method')?->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:payment_methods,slug,' . $paymentMethodId,
            'type' => 'required|in:gateway,offline',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'extra_fee' => 'integer|min:0',
            'config' => 'nullable|json',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام روش پرداخت الزامی است.',
            'slug.required' => 'شناسه یکتا الزامی است.',
            'slug.unique' => 'این شناسه قبلاً استفاده شده است.',
            'type.in' => 'نوع روش پرداخت باید gateway یا offline باشد.',
            'extra_fee.integer' => 'هزینه اضافی باید عدد باشد.',
            'config.json' => 'تنظیمات اضافی باید JSON معتبر باشد.',
        ];
    }
}
