<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:settings,name,' . $this->setting->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'notification_number' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_open' => 'boolean',
            'temp_reserve_time' => 'integer|min:0',
            'automatic_order_confirmation' => 'boolean',
            'tax_percentage' => 'integer|min:0|max:100',
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
            'name.required' => 'نام فروشگاه الزامی است.',
            'name.string' => 'نام باید متن باشد.',
            'name.max' => 'نام نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'name.unique' => 'نام فروشگاه قبلا ثبت شده است.',
            'description.string' => 'توضیحات باید متن باشد.',
            'logo.string' => 'لوگو باید متن باشد.',
            'logo.max' => 'لوگو نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'category.string' => 'دسته‌بندی باید متن باشد.',
            'category.max' => 'دسته‌بندی نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'notification_number.max' => 'شماره اعلان نمی‌تواند بیش از ۲۰ کاراکتر باشد.',
            'latitude.numeric' => 'عرض جغرافیایی باید عدد باشد.',
            'latitude.between' => 'عرض جغرافیایی باید بین -۹۰ تا ۹۰ باشد.',
            'longitude.numeric' => 'طول جغرافیایی باید عدد باشد.',
            'longitude.between' => 'طول جغرافیایی باید بین -۱۸۰ تا ۱۸۰ باشد.',
            'is_open.boolean' => 'وضعیت باز بودن باید درست یا نادرست باشد.',
            'temp_reserve_time.integer' => 'زمان رزرو باید عدد صحیح باشد.',
            'temp_reserve_time.min' => 'زمان رزرو نمی‌تواند منفی باشد.',
            'automatic_order_confirmation.boolean' => 'تأیید خودکار سفارش باید درست یا نادرست باشد.',
            'tax_percentage.integer' => 'درصد مالیات باید عدد صحیح باشد.',
            'tax_percentage.min' => 'درصد مالیات نمی‌تواند کمتر از ۰ باشد.',
            'tax_percentage.max' => 'درصد مالیات نمی‌تواند بیش از ۱۰۰ باشد.',
        ];
    }
}
