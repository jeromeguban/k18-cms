<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CategoryRequest extends FormRequest
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
            'image'           => 'required|image|mimes:png||dimensions:min_width=150,min_height=150,max_width=150,max_height=150',
            'category_code'   => 'required|max:191|unique:categories,category_code',
            'category_name'   => 'required|max:191',
            'featured'        => 'nullable'
        ];
    }

    private function updateValidation()
    {
        return [
            'category_code'   => [
                'max:45',
                Rule::unique('categories')->ignore(request()->category_id, 'category_id')
            ],
            'category_name'   => 'required|max:191',
            
        ];
    }
}
