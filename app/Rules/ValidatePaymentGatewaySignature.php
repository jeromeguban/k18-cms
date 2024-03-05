<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePaymentGatewaySignature implements Rule
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
        return $value == sha1(request()->req_id.request()->status.'{'.env('PAYMENT_GATEWAY_CLIENT_SECRET').'}');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Payment Gateway Signature.';
    }
}
