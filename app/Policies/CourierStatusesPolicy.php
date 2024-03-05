<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CourierStatuses;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourierStatusesPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = CourierStatuses::class;

    /**
     * Determine whether the user hasPermission list brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.courier-status');

    }

    /**
     * Determine whether the user hasPermission view the courier-status.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourierStatuses  $delivery-status
     * @return mixed
     */
    public function view(User $user, CourierStatuses $courierStatus)
    {

        return $user->hasPermission('view.courier-status');

    }

    /**
     * Determine whether the user hasPermission create courierStatus.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.courier-status');

    }

    /**
     * Determine whether the user hasPermission update the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourierStatuses  $courierStatus
     * @return mixed
     */
    public function update(User $user, CourierStatuses $courierStatus)
    {

        return $user->hasPermission('update.courier-status');

    }

    /**
     * Determine whether the user hasPermission delete the deliveryStatuses.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourierStatuses  $deliveryStatuses
     * @return mixed
     */
    public function delete(User $user, CourierStatuses $courierStatus)
    {

        return $user->hasPermission('delete.courier-status');

    }

    /**
     * Determine whether the user hasPermission restore the deliveryStatuses.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourierStatuses  $deliveryStatuses
     * @return mixed
     */
    public function restore(User $user, CourierStatuses $courierStatus)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the courierStatus.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourierStatuses  $courierStatus
     * @return mixed
     */
    public function forceDelete(User $user, CourierStatuses $courierStatus)
    {
        //
    }
}
