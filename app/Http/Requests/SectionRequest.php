<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredParameters;

class SectionRequest extends FormRequest
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
            'name'                  => 'required',
            'section_label'         => 'required',
            'section_type'          => 'required',
            'link'                  => 'nullable|max:500',
            'banner'                => 'nullable',
            'banner.*.image'        => 'nullable|image|mimes:jpeg,gif,png',
            'mobile_banner'         => 'nullable',
            'mobile_banner.*.image' => 'nullable|image|mimes:jpeg,gif.png',
            'type'                  => 'nullable',
            'parameters'            => [
                'nullable',
                new RequiredParameters(request()->section_type)
            ],
        ];
    }


    protected function updateValidation()
    {
        return [
            'name'              => 'required',
            'section_type'      => 'required',
            'section_label'     => 'required',
            'link'              => 'required|max:500',
            'parameters'            => [
                'nullable',
                new RequiredParameters(request()->section_type)
            ],
            // 'parameters.categories' => [
            //     'required_without_all:parameters.tags',
            //     'array',
            // ],
            // 'parameters.tags' => [
            //     'required_without_all:parameters.categories',
            //     'array',
            // ],
        ];
    }

    public function attributes()
    {
        return [
            'name'          => 'Name',
            'section_label' => 'Label',
            'link'          => 'Link',
            'banner'        => 'Banner',
            'mobile_banner' => 'Mobile Banner',
            'type'          => 'Type',
            'parameters'    => 'Categories or Tags',
        ];
    }

     /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $parameters = $this->input('parameters');

        if (is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        }

        if (!is_array($parameters)) {
            $parameters = [];
        }

        $this->merge([
            'parameters' => $parameters,
        ]);
    }

}
