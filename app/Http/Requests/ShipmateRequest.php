<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckOrderIsValidForWaybill;
use App\Rules\CheckIfOrderIsBookByHMR;

class ShipmateRequest extends FormRequest
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
            'order_id'      => [
                'required',
                new CheckOrderIsValidForWaybill,
                new CheckIfOrderIsBookByHMR
            ],
            'courier_service'       => 'required',
            'pickup_address'      => 'required',
            'drop_off_address'      => 'required',
            'from_address.contact_name' => 'required',
            'from_address.contact_number' => 'required',
            'from_address.address1' => 'required',
            'from_address.city' => 'required',
            'from_address.province' => 'required',
            'from_address.zip' => 'required',
            // 'from_address.remarks' => 'required',
            'from_address.latitude' => 'required',
            'from_address.longitude' => 'required',
            'to_address.contact_name' => 'required',
            'to_address.contact_number' => 'required',
            'to_address.address1' => 'required',
            'to_address.city' => 'required',
            'to_address.province' => 'required',
            'to_address.zip' => 'required',
            // 'to_address.remarks' => 'required',
            'to_address.latitude' => 'required',
            'to_address.longitude' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'order_id'  => 'Order',
            'courier_service'  => 'Courier',
            'pickup_address' => 'Pickup Address',
            'drop_off_address'    => 'Dropoff Address',
            'from_address.contact_name' => 'Pickup Address Contact Person',
            'from_address.contact_number' => 'Pickup Address Contact Number',
            'from_address.address1' => 'Pickup Address Address',
            'from_address.city' => 'Pickup Address City',
            'from_address.province' => 'Pickup Address Province',
            'from_address.country' => 'Pickup Address Country',
            'from_address.zip' => 'Pickup Address Zip',
            'from_address.landmark' => 'Pickup Address Landmark',
            'from_address.remarks' => 'Pickup Address Remarks',
            'from_address.latitude' => 'Pickup Address Latitude',
            'from_address.longitude' => 'Pickup Address Longitude',
            'to_address.contact_name' => 'Drop Off Address Contact Person',
            'to_address.contact_number' => 'Drop Off Address Contact Number',
            'to_address.address1' => 'Drop Off Address Address',
            'to_address.city' => 'Drop Off Address City',
            'to_address.province' => 'Drop Off Address Province',
            'to_address.zip' => 'Drop Off Address Zip',
            'to_address.landmark' => 'Drop Off Address Landmark',
            'to_address.remarks' => 'Drop Off Address Remarks',
            'to_address.latitude' => 'Drop Off Address Latitude',
            'to_address.longitude' => 'Drop Off Address Longitude',
        ];
    }
}
