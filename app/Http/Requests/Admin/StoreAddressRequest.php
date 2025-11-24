<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'نام گیرنده الزامی است.',
            'receiver_phone.required' => 'شماره تماس گیرنده الزامی است.',
            'address.required' => 'آدرس الزامی است.',
            'postal_code.required' => 'کد پستی الزامی است.',
            'province.required' => 'استان الزامی است.',
            'city.required' => 'شهر الزامی است.',
        ];
    }
}
