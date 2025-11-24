<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingMethodRequest extends FormRequest
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
        $shippingId = $this->route('shipping_method')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shipping_methods')->ignore($shippingId),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'price' => 'required|integer|min:0',
            'config' => 'nullable|json',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام روش ارسال الزامی است.',
            'name.max' => 'نام روش ارسال نمی‌تواند بیش از 255 کاراکتر باشد.',
            'name.unique' => 'این نام قبلاً استفاده شده است.',
            'slug.required' => 'شناسه یکتا (slug) الزامی است.',
            'slug.max' => 'شناسه یکتا نمی‌تواند بیش از 255 کاراکتر باشد.',
            'slug.unique' => 'این شناسه قبلاً استفاده شده است.',
            'cost.required' => 'هزینه روش ارسال الزامی است.',
            'cost.integer' => 'هزینه باید عدد صحیح باشد.',
            'cost.min' => 'هزینه نمی‌تواند کمتر از صفر باشد.',
            'config.json' => 'فرمت پیکربندی باید JSON معتبر باشد.',
        ];
    }
}
