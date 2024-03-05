<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SubCategoryRequest  extends FormRequest
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
            'sub_category_code' => [
                'max:191',  
                Rule::unique('sub_categories')->ignore(request()->sub_category_id, 'sub_category_id')
                    ->where(function ($query) {
                        return $query->where('category_id', request()->category->category_id);
                    })
            ],
            'sub_category_name' => 'required|max:191',
        ];
    }

    private function updateValidation()
    {
        return [
            'sub_category_code' => [
                'max:45',
                Rule::unique('sub_categories')->ignore(request()->sub_category_id, 'sub_category_id')
                    ->where(function ($query) {
                        return $query->where('category_id', request()->category['category_id']);
                    })
            ],
            'sub_category_name' => 'required|max:45',
             'category_id' => 'required',
        ];
    }
}
