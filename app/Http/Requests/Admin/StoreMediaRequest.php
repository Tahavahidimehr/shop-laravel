<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:10240',
            'type' => 'required|in:image,video',
            'alt' => 'nullable|string|max:255',
            'is_main' => 'nullable|boolean',
            'folder' => 'nullable|string|max:100',
            'mediable_id' => 'nullable|integer',
            'mediable_type' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'فایل مدیا الزامی است.',
            'file.file' => 'فایل انتخاب شده معتبر نیست.',
            'file.mimes' => 'فرمت فایل باید jpg, jpeg, png, gif, mp4 یا mov باشد.',
            'file.max' => 'حجم فایل نباید بیشتر از 10 مگابایت باشد.',
            'type.required' => 'نوع مدیا الزامی است.',
            'type.in' => 'نوع مدیا باید image یا video باشد.',
            'folder.string' => 'نام پوشه باید رشته باشد.',
            'alt.string' => 'متن جایگزین باید رشته باشد.',
            'alt.max' => 'متن جایگزین نباید بیشتر از 255 کاراکتر باشد.',
            'is_main.boolean' => 'مقدار is_main باید true یا false باشد.',
        ];
    }
}
