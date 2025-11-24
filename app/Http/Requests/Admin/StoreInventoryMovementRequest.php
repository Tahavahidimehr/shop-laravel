<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'movable_type' => 'nullable|string',
            'movable_id' => 'nullable|integer',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_id.required' => 'انتخاب انبار الزامی است.',
            'warehouse_id.exists' => 'انبار انتخاب شده معتبر نیست.',
            'type.required' => 'نوع حرکت الزامی است.',
            'type.in' => 'نوع حرکت باید "in" یا "out" باشد.',
            'quantity.required' => 'تعداد الزامی است.',
            'quantity.integer' => 'تعداد باید عدد صحیح باشد.',
            'quantity.min' => 'تعداد نمی‌تواند کمتر از ۱ باشد.',
            'movable_type.string' => 'نوع آیتم باید یک متن باشد.',
            'movable_id.integer' => 'شناسه آیتم باید یک عدد صحیح باشد.',
            'note.string' => 'یادداشت باید یک متن باشد.',
            'note.max' => 'یادداشت نمی‌تواند بیش از ۵۰۰ کاراکتر باشد.',
        ];
    }
}
