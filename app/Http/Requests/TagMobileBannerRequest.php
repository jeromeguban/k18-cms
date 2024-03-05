<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TagMobileBannerRequest extends FormRequest
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
        }
    }

    private function createValidation()
    {
        return [
            'mobile_banner' => 'required|image|mimes:jpeg,png||dimensions:min_width=694,min_height=196,max_width=694,max_height=196',
        ];
    }
}
