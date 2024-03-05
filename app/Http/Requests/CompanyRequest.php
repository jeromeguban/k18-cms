<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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

    protected function createValidation()
    {

        return [
            'company_name'  => 'required|unique:companies',
            'company_code'  => 'required|unique:companies'
        ];
    }

    protected function updateValidation()
    {
        return [
            'company_name'  => [
                'required',
                Rule::unique('companies', 'company_name')->ignore(request()->id)
            ],
            'company_code'  => [
                'required',
                Rule::unique('companies', 'company_code')->ignore(request()->id)
            ]
        ];
    }

    public function attributes()
    {

        return [
            'company_name'  => 'Company Name',
            'company_code'  => 'Company Code'
        ];
    }
}
