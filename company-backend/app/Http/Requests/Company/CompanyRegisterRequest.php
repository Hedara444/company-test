<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterRequest extends FormRequest
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
            'email'=>'required|email|unique:companies',
            'country'=>'required|string|max:255',
            'industry'=>'required|string|max:255',
            'phone' => 'required|unique:companies|regex:/^\+\d{1,3}-\d{1,4}\d{1,4}\d{1,4}$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The company name is required.',
            'name.unique'=>'There are already a company with that name.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'There are already a company with that email.',
            'email.email' => 'The email address must be a valid email format.',
            "country.required"=>"The company country is required.",
            "industry.required"=>"The company industry is required.",
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be a valid phone number. expected format : +1-2223334440 .',
        ];
    }
}
