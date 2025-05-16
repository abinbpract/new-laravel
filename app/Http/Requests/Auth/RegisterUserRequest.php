<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|min:8',
            'first_name'         => 'required',
            'last_name'          => 'required',
            'address1'           => 'required',
            'city'               => 'required',
            'state'              => 'required',
            'country'            => 'required',
            'mobile_number'      => 'required',
            'national_id_type'   => 'required',
            'national_id_number' => 'required',
            'date_of_birth'      => 'required|date',
        ];
    }
    public function messages()
    {
        return [
            'email.required'              => 'The email is required.',
            'password.required'           => 'The password is required.',
            'first_name.required'         => 'First name is required.',
            'last_name.required'          => 'Last name is required.',
            'address1.required'           => 'Address is required.',
            'city.required'               => 'City is required.',
            'state.required'              => 'State is required.',
            'country.required'            => 'Country is required.',
            'country_code.required'       => 'Country code is required.',
            'mobile_number.required'      => 'Mobile number is required.',
            'national_id_type.required'   => 'National ID type is required.',
            'national_id_number.required' => 'National ID number is required.',
            'date_of_birth.required'      => 'Date of birth is required.',
        ];
    }
}
