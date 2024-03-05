<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class QuickLinkRequest extends FormRequest
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
            'icon'   => 'required|image|mimes:png||dimensions:min_width=280,min_height=160,max_width=280,max_height=160',
            'link'   => 'required|max:255',
            'label'  => 'required|max:255'
        ];

    }

    protected function updateValidation()
    {

        return [
            'icon'   => 'required|image|mimes:png||dimensions:min_width=280,min_height=160,max_width=280,max_height=160',
            'link'   => 'required|max:255',
            'label'  => 'required|max:255'
        ];

    }

    public function attributes()
    {

        return [
            'label'  => 'Quick Link Name',
            'link'  => 'Quick Link'
        ];

    }
}
