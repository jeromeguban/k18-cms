<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name'              => 'required|max:191|unique:roles,name',
            'description'     => 'required|max:191',
        ];
    }

    private function updateValidation()
    {
        return [
            'name' => [
                'required', 'max:191',
                Rule::unique('roles', 'name')->ignore(request()->id)
            ],
            'description'     => 'required|max:191',
        ];
    }

    public function attributes()
    {
        return [
            'name'          => 'Role Name',    
            'description'   => 'Role Description',
        ];
    }
}
