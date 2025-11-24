<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReturnRequest extends FormRequest
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
            'order_id' => ['required', 'exists:orders,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:500'],
            'refund_amount' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'returned_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'انتخاب سفارش الزامی است.',
            'order_id.exists' => 'سفارش انتخاب‌شده معتبر نیست.',
            'user_id.exists' => 'کاربر انتخاب‌شده معتبر نیست.',
            'reason.required' => 'لطفاً دلیل مرجوعی را وارد کنید.',
            'reason.string' => 'دلیل مرجوعی باید به صورت متن باشد.',
            'refund_amount.integer' => 'مبلغ بازگشت باید عدد باشد.',
            'refund_amount.min' => 'مبلغ بازگشت نمی‌تواند منفی باشد.',
            'returned_at.date' => 'تاریخ مرجوعی معتبر نیست.',
        ];
    }
}
