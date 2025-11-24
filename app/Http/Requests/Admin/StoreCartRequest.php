<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id|unique:carts,user_id',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'شناسه کاربر الزامی است.',
            'user_id.exists' => 'کاربر مورد نظر وجود ندارد.',
            'user_id.unique' => 'این کاربر قبلاً یک سبد خرید دارد.',
        ];
    }
}
