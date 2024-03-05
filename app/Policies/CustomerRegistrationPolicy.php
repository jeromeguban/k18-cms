<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustomerRegistration;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerRegistrationPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = CustomerRegistration::class;

    /**
     * Determine whether the user hasPermission list customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.customer-registration');

    }

    /**
     * Determine whether the user hasPermission view the customers.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRegistration $customer_registration
     * @return mixed
     */
    public function view(User $user, CustomerRegistration$customer_registration)
    {

        return $user->hasPermission('view.customer-registration');

    }

    /**
     * Determine whether the user hasPermission create customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.customer-registration');

    }

    /**
     * Determine whether the user hasPermission update the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRegistration $customer_registration
     * @return mixed
     */
    public function update(User $user, CustomerRegistration $customer_registration)
    {

        return $user->hasPermission('update.customer-registration');

    }

    /**
     * Determine whether the user hasPermission delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRegistration  $customer_registration
     * @return mixed
     */
    public function delete(User $user, CustomerRegistration $customer_registration)
    {

        return $user->hasPermission('delete.customer-registration');

    }

    /**
     * Determine whether the user hasPermission restore the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRegistration  $customer_registration
     * @return mixed
     */
    public function restore(User $user, CustomerRegistration $customer_registration)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerRegistration  $customer_registration
     * @return mixed
     */
    public function forceDelete(User $user, CustomerRegistration $customer_registration)
    {
        //
    }
}
