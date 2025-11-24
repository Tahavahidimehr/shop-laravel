<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountUserUsageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'used_count' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'used_count.required' => 'تعداد استفاده الزامی است.',
            'used_count.integer' => 'تعداد استفاده باید یک عدد صحیح باشد.',
            'used_count.min' => 'تعداد استفاده نمی‌تواند منفی باشد.',
        ];
    }
}
