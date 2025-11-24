<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'attribute_id'=>'required|exists:attributes,id',
            'value'=>'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'attribute_id.required'=>'ویژگی الزامی است',
            'attribute_id.exists'=>'ویژگی انتخاب شده معتبر نیست',
            'value.required'=>'مقدار الزامی است',
            'value.string'=>'مقدار باید متن باشد',
            'value.max'=>'مقدار نمی‌تواند بیش از ۲۵۵ کاراکتر باشد',
        ];
    }
}
