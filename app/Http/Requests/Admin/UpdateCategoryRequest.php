<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->category),
            ],
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'فیلد نام الزامی است.',
            'name.string' => 'نام باید متن باشد.',
            'name.max' => 'نام نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'slug.required' => 'فیلد اسلاگ الزامی است.',
            'slug.unique' => 'این اسلاگ قبلا استفاده شده است.',
            'parent_id.exists' => 'دسته والد انتخاب شده معتبر نیست.',
            'description.string' => 'توضیحات باید متن باشد.',
            'is_active.boolean' => 'فیلد فعال/غیرفعال باید true یا false باشد.',
        ];
    }
}
