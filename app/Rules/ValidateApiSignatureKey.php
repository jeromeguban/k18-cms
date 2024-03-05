<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ValidateApiSignatureKey implements Rule
{
    protected $salt;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($salt = null)
    {
        $this->salt = $salt;
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
        return sha1($this->salt."{".env('API_SIGNATURE_KEY')."}") == $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Api Signature Key.';
    }
}
