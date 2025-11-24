<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductReturnRequest extends FormRequest
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
            'status' => ['nullable', 'in:pending,approved,rejected,refunded'],
            'approved_by' => ['nullable', 'exists:users,id'],
            'refund_amount' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'وضعیت انتخاب‌شده معتبر نیست.',
            'approved_by.exists' => 'کاربر تأییدکننده معتبر نیست.',
            'refund_amount.integer' => 'مبلغ بازگشت باید عدد باشد.',
            'refund_amount.min' => 'مبلغ بازگشت نمی‌تواند منفی باشد.',
        ];
    }
}
