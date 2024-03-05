<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CostRequest extends FormRequest
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
            'store_id'      => 'required|exists:stores,id',
            'cost_type_id'  => 'required|exists:cost_types,id',
            'amount'        => 'required|numeric'
        ];
    }

    protected function updateValidation()
    {

        return [
            'store_id'      => 'required|exists:stores,id',
            'cost_type_id'  => 'required|exists:cost_types,id',
            'amount'        => 'required|numeric'
        ];
    }

    public function attributes()
    {

        return [
            'store_id'      => 'Store',
            'cost_type_id'  => 'Cost Type',
            'amount'        => 'Amount'
        ];
    }
}
