<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CostTypeRequest extends FormRequest
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
            'type'  => 'required|unique:cost_types'
        ];
    }

    protected function updateValidation()
    {
        return [
            'type'  => [
                'required',
                Rule::unique('cost_types', 'type')->ignore(request()->id)
            ]
        ];
    }

    public function attributes()
    {

        return [
             'type'  => 'Type'
        ];
    }
}
