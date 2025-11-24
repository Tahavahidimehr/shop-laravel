<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariantValueRequest extends FormRequest
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
            'variant_id' => ['required', 'exists:variants,id'],
            'value' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.required' => 'واریانت مربوطه الزامی است.',
            'variant_id.exists' => 'واریانت مورد نظر وجود ندارد.',
            'value.required' => 'مقدار واریانت الزامی است.',
            'value.string' => 'مقدار واریانت باید متن باشد.',
            'value.max' => 'مقدار واریانت نباید بیش از ۲۵۵ کاراکتر باشد.',
        ];
    }
}
