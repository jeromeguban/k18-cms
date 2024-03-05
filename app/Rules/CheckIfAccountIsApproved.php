<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckIfAccountIsApproved implements Rule
{
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
         
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

        $this->user = User::where($attribute, $value)
                    ->first();

        if(!$this->user)
            return false;

        return $this->user->status == 'Approved';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if(!$this->user)
            return 'These credentials do not match our records.';

        return 'The Account is not yet Approved.';
    }
}
