<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryStockRequest extends FormRequest
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
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'stockable_type' => 'nullable|string',
            'stockable_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
            'average_cost' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_id.required' => 'انتخاب انبار الزامی است.',
            'warehouse_id.exists' => 'انبار انتخاب شده معتبر نیست.',
            'product_id.exists' => 'محصول انتخاب شده معتبر نیست.',
            'product_variant_id.exists' => 'تنوع محصول انتخاب شده معتبر نیست.',
            'quantity.required' => 'تعداد وارد شده الزامی است.',
            'quantity.integer' => 'تعداد باید عدد صحیح باشد.',
            'quantity.min' => 'تعداد باید حداقل 1 باشد.',
        ];
    }
}
