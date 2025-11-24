<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackagingTypeRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'لطفا نام نوع بسته‌بندی را وارد کنید',
            'name.string' => 'نام باید متن باشد',
            'name.max' => 'نام نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'description.string' => 'توضیحات باید متن باشد',
            'price.required' => 'لطفا قیمت را وارد کنید',
            'price.integer' => 'قیمت باید عدد صحیح باشد',
            'price.min' => 'قیمت نمی‌تواند منفی باشد',
            'is_active.boolean' => 'وضعیت فعال باید true یا false باشد',
        ];
    }
}
