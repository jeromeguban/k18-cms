<?php

namespace App\Rules;

use App\Models\CustomerLoginCredential;
use Illuminate\Contracts\Validation\Rule;

class CheckIfUsernameAlreadyExist implements Rule
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
        return !CustomerLoginCredential::where('username_index', md5($value))->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exist.';
    }
}
