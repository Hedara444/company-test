<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRegisterRequest extends FormRequest
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
            'name'=>'required|string|unique:companies|max:255',
            'email'=>'required|email',
            'companyId'=>'required|integer',
            'phone' => 'required|regex:/^\+\d{1,3}-\d{1,4}\d{1,4}\d{1,4}$/',
        ];
    }
}
