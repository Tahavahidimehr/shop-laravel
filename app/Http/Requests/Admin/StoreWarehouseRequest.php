<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string|max:500',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام انبار الزامی است.',
            'name.string' => 'نام انبار باید یک متن باشد.',
            'name.max' => 'نام انبار نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'code.string' => 'کد انبار باید یک متن باشد.',
            'code.max' => 'کد انبار نمی‌تواند بیش از ۵۰ کاراکتر باشد.',
            'code.unique' => 'این کد قبلاً ثبت شده است.',
            'address.string' => 'آدرس باید یک متن باشد.',
            'address.max' => 'آدرس نمی‌تواند بیش از ۵۰۰ کاراکتر باشد.',
            'province.string' => 'استان باید یک متن باشد.',
            'province.max' => 'استان نمی‌تواند بیش از ۱۰۰ کاراکتر باشد.',
            'city.string' => 'شهر باید یک متن باشد.',
            'city.max' => 'شهر نمی‌تواند بیش از ۱۰۰ کاراکتر باشد.',
        ];
    }
}
