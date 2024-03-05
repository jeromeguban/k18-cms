<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DeliveryStatuses;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryStatusesPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = DeliveryStatuses::class;

    /**
     * Determine whether the user hasPermission list brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.delivery-status');

    }

    /**
     * Determine whether the user hasPermission view the delivery-status.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeliveryStatuses  $delivery-status
     * @return mixed
     */
    public function view(User $user, DeliveryStatuses $deliveryStatus)
    {

        return $user->hasPermission('view.delivery-status');

    }

    /**
     * Determine whether the user hasPermission create brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.delivery-status');

    }

    /**
     * Determine whether the user hasPermission update the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeliveryStatuses  $deliveryStatus
     * @return mixed
     */
    public function update(User $user, DeliveryStatuses $deliveryStatus)
    {

        return $user->hasPermission('update.delivery-status');

    }

    /**
     * Determine whether the user hasPermission delete the deliveryStatuses.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeliveryStatuses  $deliveryStatuses
     * @return mixed
     */
    public function delete(User $user, DeliveryStatuses $deliveryStatus)
    {

        return $user->hasPermission('delete.delivery-status');

    }

    /**
     * Determine whether the user hasPermission restore the deliveryStatuses.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeliveryStatuses  $deliveryStatuses
     * @return mixed
     */
    public function restore(User $user, DeliveryStatuses $deliveryStatus)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the deliveryStatus.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeliveryStatuses  $deliveryStatus
     * @return mixed
     */
    public function forceDelete(User $user, DeliveryStatuses $deliveryStatus)
    {
        //
    }
}
