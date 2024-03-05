<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NavigationRequest extends FormRequest
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
            'type' => 'required',
            'label' => 'required',
            'link' => 'nullable',
            'icon' => 'nullable',
            'properties' => 'required_if:type,==,Footer',
            'properties.*.label' => 'required_if:type,==,Footer',
            'properties.*.icon' => 'nullable',
            'properties.*.link' => 'required_if:type,==,Footer',
        ];
    }

    protected function updateValidation()
    {
        return [
            'type' => 'required',
            'label' => 'required',
            'link' => 'nullable',
            'icon' => 'nullable',
            'properties' => 'required_if:type,==,Footer',
            'properties.*.label' => 'required_if:type,==,Footer',
            'properties.*.icon' => 'nullable',
            'properties.*.link' => 'required_if:type,==,Footer',
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'Type',
            'label' => 'Label',
            'link' => 'Link',
            'icon' => 'Icon',
            'properties' => 'Properties',
            'properties.*.label' => 'Label',
            'properties.*.icon' => 'Icon',
            'properties.*.link' => 'Link',
        ];
    }
}
