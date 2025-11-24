<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'nullable|exists:users,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'payment_method_name' => 'required|string|max:255',
            'reference_id' => 'nullable|string|max:255',
            'status' => 'required|in:pending,success,failed,canceled',
            'price_to_pay' => 'required|integer|min:0',
            'verify_response' => 'nullable|json',
            'card_number' => 'nullable|string|max:25',
            'paid_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'شناسه سفارش لازم است.',
            'order_id.exists' => 'سفارشی با این شناسه وجود ندارد.',
            'user_id.exists' => 'کاربر انتخاب شده معتبر نیست.',
            'payment_method_id.exists' => 'روش پرداخت انتخاب شده معتبر نیست.',
            'payment_method_name.required' => 'نام روش پرداخت لازم است.',
            'payment_method_name.string' => 'نام روش پرداخت باید متن باشد.',
            'payment_method_name.max' => 'نام روش پرداخت نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'reference_id.string' => 'شناسه مرجع باید متن باشد.',
            'reference_id.max' => 'شناسه مرجع نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'status.required' => 'وضعیت تراکنش لازم است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نیست.',
            'price_to_pay.required' => 'مبلغ قابل پرداخت لازم است.',
            'price_to_pay.integer' => 'مبلغ قابل پرداخت باید عدد باشد.',
            'price_to_pay.min' => 'مبلغ قابل پرداخت نمی‌تواند منفی باشد.',
            'verify_response.json' => 'پاسخ تأیید باید در قالب JSON باشد.',
            'card_number.string' => 'شماره کارت باید متن باشد.',
            'card_number.max' => 'شماره کارت نمی‌تواند بیش از ۲۵ کاراکتر باشد.',
            'paid_at.date' => 'تاریخ پرداخت معتبر نیست.',
        ];
    }
}
