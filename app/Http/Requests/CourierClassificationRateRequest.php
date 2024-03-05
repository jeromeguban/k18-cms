<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourierClassificationRateRequest extends FormRequest
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
        return array_merge([
            'origin_classification_id'    => [
                'required',
            ],
            'destination_classification_id'    => [
                'required',
            ],
        ], $this->getClassificationRateRowRules());
    }

    public function attributes()
    {
        return  array_merge([
            'origin_classification_id'    => 'Origin',
            'destination_classification_id'    => 'Destination',
        ], $this->getClassificationRateRowAttributes());
    }


    private function getClassificationRateRowRules()
    {

        $rules = [];
        if (count(request()->rates) > 0) {
            foreach (request()->rates as $index => $increment_row) {
                $rules['rates.' . $index . '.minimum_weight'] = [
                    'required',
                    'numeric',
                    $index == count(request()->rates) - 1 ? '' : 'lt:rates.' . $index . '.maximum_weight',
                    $index > 0 ? 'gte:rates.' . ($index - 1) . '.maximum_weight' : ''
                ];
                $rules['rates.' . $index . '.maximum_weight'] = [
                    $index == count(request()->rates) - 1 ? 'nullable' : 'required',
                    'numeric',
                    'gt:rates.' . $index . '.minimum_weight'
                ];
                $rules['rates.' . $index . '.decrement_weight'] = 'required|numeric';
                $rules['rates.' . $index . '.price'] = 'required|numeric';
                $rules['rates.' . $index . '.increment'] = 'required|numeric';
            }
        }

        return $rules;
    }

    private function getClassificationRateRowAttributes()
    {

        $attributes = [];

        if (count(request()->rates) > 0) {
            foreach (request()->rates as $index => $increment_row) {
                $row = $index + 1;
                $attributes['rates.' . $index . '.minimum_weight'] = "Row $row Minimum Weight";
                $attributes['rates.' . $index . '.maximum_weight'] = "Row $row Maximum Weight";
                $attributes['rates.' . $index . '.decrement_weight'] = "Row $row Decrement Weight";
                $attributes['rates.' . $index . '.price'] = "Row $row Price";
                $attributes['rates.' . $index . '.increment'] = "Row $row Increment";
            }
        }

        return $attributes;
    }
}
