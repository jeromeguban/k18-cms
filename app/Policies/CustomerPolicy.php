<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Customer::class;

    /**
     * Determine whether the user hasPermission list customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.customer');

    }

    /**
     * Determine whether the user hasPermission view the customers.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function view(User $user, Customer $customer)
    {

        return $user->hasPermission('view.customer');

    }

    /**
     * Determine whether the user hasPermission create customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.customer');

    }

    /**
     * Determine whether the user hasPermission update the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function update(User $user, Customer $customer)
    {

        return $user->hasPermission('update.customer');

    }

    /**
     * Determine whether the user hasPermission delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {

        return $user->hasPermission('delete.customer');

    }

    /**
     * Determine whether the user hasPermission restore the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function restore(User $user, Customer $customer)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        //
    }
}
