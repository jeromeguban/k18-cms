<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuleRequest extends FormRequest
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
            'name'                          => 'required|max:191|unique:modules,name',
            'module_permissions'            => 'required|array',
            'module_permissions.*'          => 'required|max:191',
        ];
    }

    private function updateValidation()
    {
        return [
            'name' => [
                'required', 'max:191',
                Rule::unique('modules', 'name')->ignore(request()->id)
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => 'Module Name',    
            'module_permissions'    => 'Permissions',
        ];
    }
}
