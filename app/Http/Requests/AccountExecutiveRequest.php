<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountExecutiveRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'user_id' => 'required|unique:account_executives,user_id',
        ];
    }

    protected function updateValidation()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_id' => [
                Rule::unique('account_executives', 'user_id')->ignore(request()->id),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'user_id' => 'User',
        ];
    }
}
