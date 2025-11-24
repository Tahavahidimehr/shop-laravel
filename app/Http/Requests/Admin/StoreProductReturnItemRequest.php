<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReturnItemRequest extends FormRequest
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
            'product_return_id' => ['required', 'exists:product_returns,id'],
            'returnable_type' => ['required', 'string'],
            'returnable_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'refund_amount' => ['nullable', 'integer', 'min:0'],
            'cost_price' => ['nullable', 'integer', 'min:0'],
            'profit_loss' => ['nullable', 'integer'],
            'condition' => ['nullable', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_return_id.required' => 'شناسه مرجوعی الزامی است.',
            'product_return_id.exists' => 'شناسه مرجوعی معتبر نیست.',
            'returnable_type.required' => 'نوع آیتم مرجوعی الزامی است.',
            'returnable_id.required' => 'شناسه آیتم مرجوعی الزامی است.',
            'quantity.required' => 'تعداد مرجوعی الزامی است.',
            'quantity.min' => 'تعداد مرجوعی نمی‌تواند کمتر از ۱ باشد.',
            'refund_amount.integer' => 'مبلغ بازگشت باید عدد باشد.',
            'cost_price.integer' => 'قیمت تمام‌شده باید عدد باشد.',
            'profit_loss.integer' => 'مقدار سود/زیان باید عدد باشد.',
            'condition.string' => 'وضعیت کالا باید به صورت متن وارد شود.',
            'reason.string' => 'دلیل مرجوعی باید به صورت متن وارد شود.',
        ];
    }
}
