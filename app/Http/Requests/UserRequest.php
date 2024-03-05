<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
 
    private function createValidation()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|numeric',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'roles'         => 'required|array',
            'roles.*.id'    => 'required|exists:roles,id',
        ];
    }

    private function updateValidation()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|numeric',
            'email'         => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore(request()->id)
            ],
        ];
    }

    public function attributes()
    {
        return [
            'first_name'    => 'First Name',
            'last_name'     => 'Last Name',
            'phone'         => 'Phone',
            'email'         => 'Email',
            'password'      => 'Password',
            'password_confirmation' => 'Password Confirmation',
            'roles'         => 'Roles',
        ];
    }
}
