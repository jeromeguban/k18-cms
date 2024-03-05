<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class BrandBannerRequest extends FormRequest
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
            'banner' => 'required|image|mimes:jpeg,png||dimensions:min_width=1260,min_height=280,max_width=1260,max_height=280',
        ];
    }
}
