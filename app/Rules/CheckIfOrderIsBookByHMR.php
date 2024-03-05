<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Order;

class CheckIfOrderIsBookByHMR implements Rule
{
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
    
        if (!$order) {
            return false;
        }

        $courierDetails = json_decode($order->courier_details, true);

        if (!$courierDetails || !isset($courierDetails['courier'])) {
            return false;
        }

        return $courierDetails['courier'] !== 'Book by HMR';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Order is Book By HMR';
    }
}
