<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryStockRequest extends FormRequest
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
            'quantity' => 'nullable|integer|min:0',
            'average_cost' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.integer' => 'تعداد باید عدد صحیح باشد.',
            'quantity.min' => 'تعداد نمی‌تواند منفی باشد.',
            'average_cost.integer' => 'قیمت باید عدد صحیح باشد.',
            'average_cost.min' => 'قیمت نمی‌تواند منفی باشد.',
        ];
    }
}
