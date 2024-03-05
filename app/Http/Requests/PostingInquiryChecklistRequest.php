<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostingInquiryChecklistRequest extends FormRequest
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
            'checklist' => [
                'nullable',
            ],
        ], $this->getChecklistRowsRules());
    }

    public function attributes()
    {
        return array_merge([
            'checklist' => 'Checklist',
        ], $this->getChecklistRowsAttributes());
    }

    private function getChecklistRowsRules()
    {

        $rules = [];

        if (count(request()->checklist) > 0) {
            foreach (request()->checklist as $index => $checklist_row) {
                $rules['checklist.' . $index . '.item'] = 'required';
            }

        }

        return $rules;

    }

    private function getChecklistRowsAttributes()
    {

        $attributes = [];

        if (count(request()->checklist) > 0) {
            foreach (request()->checklist as $index => $checklist_row) {
                $row = $index + 1;
                $attributes['checklist.' . $index . '.item'] = "Row $row Item";
            }

        }

        return $attributes;

    }
}
