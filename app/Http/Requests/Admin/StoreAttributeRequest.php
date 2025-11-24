<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:attributes,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام ویژگی الزامی است.',
            'name.string' => 'نام ویژگی باید متنی باشد.',
            'name.max' => 'نام ویژگی نمی‌تواند بیش از 255 کاراکتر باشد.',
            'name.unique' => 'این ویژگی قبلاً ثبت شده است.',
        ];
    }
}
