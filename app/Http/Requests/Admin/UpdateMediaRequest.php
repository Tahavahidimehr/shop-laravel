<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:10240',
            'type' => 'nullable|in:image,video',
            'alt' => 'nullable|string|max:255',
            'is_main' => 'nullable|boolean',
            'mediable_id' => 'nullable|integer',
            'mediable_type' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'file.file' => 'فایل انتخاب شده معتبر نیست.',
            'file.mimes' => 'فرمت فایل باید jpg, jpeg, png, gif, mp4 یا mov باشد.',
            'file.max' => 'حجم فایل نباید بیشتر از 10 مگابایت باشد.',
            'type.in' => 'نوع مدیا باید image یا video باشد.',
            'alt.string' => 'متن جایگزین باید رشته باشد.',
            'alt.max' => 'متن جایگزین نباید بیشتر از 255 کاراکتر باشد.',
            'is_main.boolean' => 'مقدار is_main باید true یا false باشد.',
        ];
    }
}
