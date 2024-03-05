<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class KeyVisualRequest extends FormRequest
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
            'banner'        => 'required|image|mimes:jpeg,png||dimensions:min_width=1656,min_height=649,max_width=1656,max_height=649',
            'mobile_banner' => 'required|image|mimes:jpeg,png||dimensions:min_width=750,min_height=290,max_width=750,max_height=290',
            'link'  => 'required|max:500',
            'name'  => 'required|max:255'
        ];

    }

    protected function updateValidation()
    {

        return [
            'link'  => 'required|max:500',
            'name'  => 'required|max:255'
        ];

    }

    public function attributes()
    {

        return [
            'name'  => 'Key Visual Name',
            'link'  => 'Key Visual Link'
        ];

    }
    
}