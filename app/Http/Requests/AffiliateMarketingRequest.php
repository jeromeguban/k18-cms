<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AffiliateMarketingRequest extends FormRequest
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
            'commission'    => 'required|numeric|between:0,101.00',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'middle_name'   => 'required',
            'phone_no'      => 'required|numeric',
            'email'         => 'required|email|unique:affiliate_marketings,email',
        ];
    }

    private function updateValidation()
    {
        return [
            'commission'    => 'required|numeric|between:0,101.00',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'middle_name'   => 'required',
            'phone_no'      => 'required|numeric',
            'email'         => [
                'required', 'email',
                Rule::unique('affiliate_marketings', 'email')->ignore(request()->id)
            ],
        ];
    }

    public function attributes()
    {
        return [
            'first_name'    => 'First Name',
            'last_name'     => 'Last Name',
            'middle_name'   => 'Last Name',
            'phone'         => 'Phone',
            'email'         => 'Email',
            'code'          => 'Code',
        ];
    }
}