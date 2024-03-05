<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StoreCourier;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreCourierPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = StoreCourier::class;

    /**
     * Determine whether the user hasPermission list vendors.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.store-courier');
    }

    /**
     * Determine whether the user hasPermission view the store_courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreCourier  $store_courier
     * @return mixed
     */
    public function view(User $user, StoreCourier $store_courier)
    {
        return $user->hasPermission('view.store-courier');
    }

    /**
     * Determine whether the user hasPermission create stores.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.store-courier');
    }

    /**
     * Determine whether the user hasPermission update the store_courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreCourier  $store_courier
     * @return mixed
     */
    public function update(User $user, StoreCourier $store_courier)
    {
        return $user->hasPermission('update.store-courier');
    }

    /**
     * Determine whether the user hasPermission delete the store_courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreCourier  $store_courier
     * @return mixed
     */
    public function delete(User $user, StoreCourier $store_courier)
    {
        return $user->hasPermission('delete.store-courier');
    }

    /**
     * Determine whether the user hasPermission restore the store_courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreCourier  $store_courier
     * @return mixed
     */
    public function restore(User $user, StoreCourier $store_courier)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the store_courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreCourier  $store_courier
     * @return mixed
     */
    public function forceDelete(User $user, StoreCourier $store_courier)
    {
        //
    }
}
