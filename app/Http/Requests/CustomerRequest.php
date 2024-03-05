<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\CheckIfEmailAlreadyExist;
use App\Rules\CheckIfMobileNoExist;
use App\Rules\CheckIfUsernameAlreadyExist;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (request()->method()) {
            case "POST":
                return $this->createValidation();
            break;
            case "PATCH":
                return $this->updateValidation();
            break;
        }
    }

    public function createValidation()
    {
        return [
            'customer_firstname'        => 'required|max:191',
            'customer_lastname'         => 'required|max:191', 
            'customer_middlename'       => 'nullable|max:191', 
            'customer_suffixname'       => 'nullable|max:191',
            'customer_company_name'     => 'nullable|max:191', 
            'customer_gender'           => [ 
                 'required', 'max:45', Rule::in(['Male', 'Female']),
                ],
            'mobile_no'                 => [
                'required',
                'numeric',
                new CheckIfMobileNoExist
            ],
            'customer_phone'            => 'nullable|max:45',
            'additional_information'    => 'nullable',
            'login_credential'          => 'nullable|boolean',
            'username'                  => [
                Rule::requiredIf(request()->login_credential),
                'max:191',
                new CheckIfUsernameAlreadyExist
            ],
            'email'                     => [
                Rule::requiredIf(request()->login_credential),
                'max:191',
                new CheckIfEmailAlreadyExist
            ],
            'password'                  => [
                Rule::requiredIf(request()->login_credential),
                'max:191'
            ],
            'password_confirmation'     => [
                Rule::requiredIf(request()->login_credential),
                'same:password'
            ],
        ];
    }

    public function updateValidation()
    {
        return [
            'customer_firstname'        => 'required|max:191', 
            'customer_lastname'         => 'required|max:191', 
            'customer_middlename'       => 'nullable|max:191', 
            'customer_suffixname'       => 'nullable|max:191',
            'customer_company_name'     => 'nullable|max:191', 
            'customer_gender'           => [ 
                'nullable', 'max:45', Rule::in(['Male', 'Female']),
            ],
            'mobile_no'                 => [
                'required',
                'numeric',
            ],
            'customer_phone'            => 'nullable|max:45', 
            'additional_information'    => 'nullable',
            'username'                  => [
                Rule::requiredIf(request()->login_credential),
                'max:191',
            ],
            'email'                     => [
                Rule::requiredIf(request()->login_credential),
                'max:191',
            ],
        ];
    }

    public function attributes()
    {

        return [
            'customer_firstname'        => 'First Name',
            'customer_lastname'         => 'Last Name',
            'customer_gender'           => 'Gender',
            'mobile_no'                 => 'Mobile Number',
            'username'                  => 'User Name',
            'email'                     => 'Email',
            'password'                  => 'Password',
            'password_confirmation'     => 'Password Confirmation'
        ];

    }
}
