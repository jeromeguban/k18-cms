<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Order;

class CheckOrderIsValidForWaybill implements Rule
{
    protected $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $order = Order::find($value);

        $courierDetails = json_decode($order->courier_details, true);

        if (isset($courierDetails['cash_on_delivery']) && $courierDetails['cash_on_delivery']) {
            return true;
        }

        if ($order->payment_id) {
            return true;
        }

        $this->message = 'The order has not been paid.';
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'The order is not valid for waybill creation.';
    }
}
