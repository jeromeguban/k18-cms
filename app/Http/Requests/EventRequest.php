<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
            'event_name'        => 'required|max:191|unique:events',
            'description'       => 'required',
            'type'              => 'required',
            'purchase_limit'    => 'nullable|numeric',
            'starting_time'     => 'required',
            'term_id'           => 'nullable',
            'access_request_info.title' => 'nullable',
            'access_request_info.from' => 'nullable',
            'access_request_info.to' => 'nullable',
            'access_request_info.hotlines' => 'nullable',
            'valid_domains'     => 'nullable',
            'valid_domains.*'   => [
                'required',
                'regex:/^@(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/',
            ],
            'restriction'       => 'boolean',
            'auto_approve'      => 'boolean',
            'category'          => 'required',
            'top_up'            => 'boolean',
            'top_up_amount'     => 'numeric',
        ];
    }

    protected function updateValidation()
    {
        return [
            'event_name'        => 'required|max:191',
            'description'       => 'required',
            'type'              => 'required',
            'purchase_limit'    => 'nullable|numeric',
            'starting_time'     => 'nullable',
            'term_id'           => 'nullable',
            'access_request_info.title' => 'nullable',
            'access_request_info.from' => 'nullable',
            'access_request_info.to' => 'nullable',
            'access_request_info.hotlines' => 'nullable',
            'valid_domains.*'   => [
                'required',
                'regex:/^@(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/',
            ],
            'restriction'       => 'boolean',
            'auto_approve'      => 'boolean',
            'category'          => 'required',
            'top_up'            => 'boolean',
            'top_up_amount'     => 'numeric',
        ];
    }


    public function attributes()
    {
        return [
            'event_name'        => 'Event Name',
            'description'       => 'Event Description',
            'type'              => 'Event Type',
            'purchase_limit'    => 'Purchase Limit',
            'starting_time'     => 'Staring Time',
            'term_id'           => 'Terms and Condition',
            'access_request_info.title' => 'Title',
            'access_request_info.from' => 'From',
            'access_request_info.to' => 'To',
            'access_request_info.hotlines' => 'Hotlines',
            'valid_domains.*'   => 'Valid Domains',
            'category'          => 'Category',
            'top_up'            => 'Top Up',
            'top_up_amount'     => 'Top Up Amount',
        ];
    }
}
