<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
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
            'quantity' => 'sometimes|required|integer|min:1',
            'unit_price' => 'sometimes|required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'تعداد باید وارد شود.',
            'quantity.integer' => 'تعداد باید عددی باشد.',
            'quantity.min' => 'تعداد نمی‌تواند کمتر از ۱ باشد.',
            'unit_price.required' => 'قیمت واحد الزامی است.',
            'unit_price.integer' => 'قیمت واحد باید عددی باشد.',
            'unit_price.min' => 'قیمت واحد نمی‌تواند منفی باشد.',
        ];
    }
}
