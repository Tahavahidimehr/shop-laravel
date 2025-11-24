<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductReturnItemRequest extends FormRequest
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
            'quantity' => ['nullable', 'integer', 'min:1'],
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
            'quantity.integer' => 'تعداد باید عدد باشد.',
            'refund_amount.integer' => 'مبلغ بازگشت باید عدد باشد.',
            'cost_price.integer' => 'قیمت تمام‌شده باید عدد باشد.',
            'profit_loss.integer' => 'مقدار سود یا زیان باید عدد باشد.',
            'condition.string' => 'وضعیت کالا باید به صورت متن وارد شود.',
            'reason.string' => 'دلیل مرجوعی باید به صورت متن وارد شود.',
        ];
    }
}
