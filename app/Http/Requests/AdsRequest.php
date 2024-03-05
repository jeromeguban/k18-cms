<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdsRequest extends FormRequest
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
            'banner'                  => (request()->orientation == 'Landscape' ? 'required|image|mimes:jpeg,png||dimensions:min_width=822,min_height=318,max_width=822,max_height=318' : 'required|image|mimes:jpeg,png||dimensions:min_width=567,min_height=731,max_width=567,max_height=731'),
            'mobile_banner'           => 'required|image|mimes:jpeg,png||dimensions:min_width=450,min_height=280,max_width=450,max_height=280',
            'ads_link'                => 'required|max:500',
            'ads_name'                => 'required|max:255',
            'orientation'             => 'required|'
        ];
    }

    protected function updateValidation()
    {

        return [
            'banner'                    => (request()->orientation == 'Landscape' ? 'required|image|mimes:jpeg,png||dimensions:min_width=822,min_height=318,max_width=822,max_height=318' : 'required|image|mimes:jpeg,png||dimensions:min_width=567,min_height=731,max_width=567,max_height=731'),
            'mobile_banner'             => 'required|image|mimes:jpeg,png||dimensions:min_width=450,min_height=280,max_width=450,max_height=280',
            'ads_link'                  => 'required|max:500',
            'ads_name'                  => 'required|max:255',
            'orientation'               => 'required|'

        ];
    }

    public function attributes()
    {

        return [
            'ads_name'  => 'Ads Name',
            'ads_link'  => 'Ads Link',
            'banner'    => 'Banner',
            'mobile_banner'  => ' Mobile Banner'
        ];
    }
}
