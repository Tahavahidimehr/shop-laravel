<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'exists:products,id'],
            'type' => ['sometimes', 'in:image,video'],
            'path' => ['sometimes', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'محصول انتخاب شده معتبر نیست.',
            'type.in' => 'نوع رسانه باید یکی از image یا video باشد.',
            'order.integer' => 'ترتیب باید یک عدد صحیح باشد.',
            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',
        ];
    }
}
