<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Courier;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourierPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Courier::class;

    /**
     * Determine whether the user hasPermission list courier.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.courier');
    }

    /**
     * Determine whether the user hasPermission view the courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Courier  $courier
     * @return mixed
     */
    public function view(User $user, Courier $courier)
    {
        return $user->hasPermission('view.courier');
    }

    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.courier');
    }

    /**
     * Determine whether the user hasPermission update the courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Courier  $courier
     * @return mixed
     */
    public function update(User $user, Courier $courier)
    {
        return $user->hasPermission('update.courier');
    }

    /**
     * Determine whether the user hasPermission delete the courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Courier  $courier
     * @return mixed
     */
    public function delete(User $user, Courier $courier)
    {
        return $user->hasPermission('delete.courier');
    }

    /**
     * Determine whether the user hasPermission restore the courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Courier  $courier
     * @return mixed
     */
    public function restore(User $user, Courier $courier)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the courier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Courier  $courier
     * @return mixed
     */
    public function forceDelete(User $user, Courier $courier)
    {
        //
    }






    // Rates Policu

    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function listRates(User $user)
    {
        return $user->hasPermission('list-rates.courier');
    }

    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function createRates(User $user)
    {
        return $user->hasPermission('create-rates.courier');
    }


    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function updateRates(User $user)
    {
        return $user->hasPermission('update-rates.courier');
    }


    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function deleteRates(User $user)
    {
        return $user->hasPermission('delete-rates.courier');
    }
}
