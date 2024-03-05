<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PostingRequest extends FormRequest
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

        return array_merge([
            'posting_type'                      => 'required',
            'item_category_type'                => 'nullable',
            'name'                              => 'required',
            'description'                       => 'required',
            'location'                          => 'required',
            'categories'                        => 'required',
            'brands'                            => 'required',
            'viewing_details'                   => 'required',
            'viewing_details.*.contact_person'  => 'nullable',
            'viewing_details.*.contact_email'   => 'nullable|email',
            'viewing_details.*.contact_number'  => 'nullable',
        ]);

    }

    protected function updateValidation()
    {
        return [
            'category'                          => 'required',
            'name'                              => 'required',
            'description'                       => 'required',
            'location'                          => 'required',
            'categories'                        => 'required',
            'brands'                            => 'required',
            'viewing_details'                   => 'required',
            'viewing_details.*.contact_person'  => 'nullable',
            'viewing_details.*.contact_email'   => 'nullable|email',
            'viewing_details.*.contact_number'  => 'nullable',
        ];

    }

    public function attributes()
    {

        return [
            'category'                          => 'Category',
            'posting_type'                      => 'Posting Type',
            'item_category_type'                => 'Item Category Type',
            'name'                              => 'Name',
            'description'                       => 'Description',
            'location'                          => 'Location',
            'categories'                        => 'Categories',
            'sub_categories'                    => 'Sub Categories',
            'brands'                            => 'Brands',
            'viewing_details'                   => 'Viewing Details',
            'viewing_details.*.contact_person'  => 'Contact Person',
            'viewing_details.*.contact_email'   => 'Contact Email',
            'viewing_details.*.contact_number'  => 'Contact Number',
        ];

    }

}
