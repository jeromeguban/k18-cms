<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostingInquiryTaskRequest extends FormRequest
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
            'task' => [
                'nullable',
            ],
        ], $this->getTaskRowsRules());
    }

    public function attributes()
    {
        return array_merge([
            'task' => 'Task',
        ], $this->getTaskRowsAttributes());
    }

    private function getTaskRowsRules()
    {

        $rules = [];

        if (count(request()->task) > 0) {
            foreach (request()->task as $index => $task_row) {
                $rules['task.' . $index . '.item'] = 'required';
            }

        }

        return $rules;

    }

    private function getTaskRowsAttributes()
    {

        $attributes = [];

        if (count(request()->task) > 0) {
            foreach (request()->task as $index => $task_row) {
                $row = $index + 1;
                $attributes['increment_table.' . $index . '.item'] = "Row $row Item";
            }

        }

        return $attributes;

    }
}
