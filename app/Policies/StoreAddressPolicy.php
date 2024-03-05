<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StoreAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreAddressPolicy extends Policy
{

    use HandlesAuthorization;

    protected $model = StoreAddress::class;

    /**
     * Determine whether the user hasPermission list brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.store-address');

    }

    /**
     * Determine whether the user hasPermission view the store-address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreAddress  $store_address
     * @return mixed
     */
    public function view(User $user, StoreAddress $store_address)
    {

        return $user->hasPermission('view.store-address');

    }

    /**
     * Determine whether the user hasPermission create brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.store-address');

    }

    /**
     * Determine whether the user hasPermission update the store-address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreAddress  $store_address
     * @return mixed
     */
    public function update(User $user, StoreAddress $store_address)
    {

        return $user->hasPermission('update.store-address');

    }

    /**
     * Determine whether the user hasPermission delete the store-address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreAddress  $store_address
     * @return mixed
     */
    public function delete(User $user, StoreAddress $store_address)
    {

        return $user->hasPermission('delete.store-address');

    }

    /**
     * Determine whether the user hasPermission restore the store-address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreAddress  $store_address
     * @return mixed
     */
    public function restore(User $user, StoreAddress $store_address)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the store-address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreAddress  $store_address
     * @return mixed
     */
    public function forceDelete(User $user, StoreAddress $store_address)
    {
        //
    }

}