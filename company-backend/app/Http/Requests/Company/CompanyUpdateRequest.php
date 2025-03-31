<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|email',
            'country'=>'sometimes|string|max:255',
            'industry'=>'sometimes|string|max:255',
            'phone' => 'sometimes|required|regex:/^\+\d{1,3}-\d{1,4}\d{1,4}\d{1,4}$/',
        ];
    }
}
