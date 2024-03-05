<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustomerLoginCredential;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerLoginCredentialPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = CustomerLoginCredential::class;

    /**
     * Determine whether the user hasPermission list customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.customer-credential');

    }

    /**
     * Determine whether the user hasPermission view the customers.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerLoginCredential  $customer-credential
     * @return mixed
     */
    public function view(User $user, CustomerLoginCredential $customerLoginCredential)
    {

        return $user->hasPermission('view.credential');

    }

    /**
     * Determine whether the user hasPermission create customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.customer-credential');

    }

    /**
     * Determine whether the user hasPermission update the customer-credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerLoginCredential  $customer-credential
     * @return mixed
     */
    public function update(User $user, CustomerLoginCredential $customerLoginCredential)
    {

        return $user->hasPermission('update.customer-credential');

    }

    /**
     * Determine whether the user hasPermission delete the customer-credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerLoginCredential  $customer-credential
     * @return mixed
     */
    public function delete(User $user, CustomerLoginCredential $customerLoginCredential)
    {

        return $user->hasPermission('delete.customer-credential');

    }

    /**
     * Determine whether the user hasPermission restore the customer-credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerLoginCredential  $customer-credential
     * @return mixed
     */
    public function restore(User $user, CustomerLoginCredential $customerLoginCredential)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the customer-credential.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerLoginCredential  $customer-credential
     * @return mixed
     */
    public function forceDelete(User $user, CustomerLoginCredential $customerLoginCredential)
    {
        //
    }
}
