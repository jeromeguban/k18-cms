<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'brand_code'  => 'required|max:45|unique:brands,brand_code',
            'brand_name'  => 'required|max:45',
        ];

    }

    protected function updateValidation()
    {

        return [
            'brand_code'  => [
                'required', 'max:45', Rule::unique('brands', 'brand_code')->ignore(request()->brand_id, 'brand_id'),
            ],
            'brand_name'  => 'required|max:45',
        ];

    }

    public function attributes()
    {

        return [
            'brand_name'  => 'Brand Name',
            'brand_code'  => 'Brand Code'
        ];

    }
    
}