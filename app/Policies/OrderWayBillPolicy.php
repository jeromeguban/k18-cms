<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OrderWayBill;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderWayBillPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = OrderWayBill::class;

    /**
     * Determine whether the user hasPermission access the order_waybill.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function access(User $user, OrderWayBill $order_waybill)
    {

        return $user->hasPermission('access.order-waybill');

    }

    /**
    * Determine whether the user hasPermission list order_waybill.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.order-waybill');
    }

    /**
     * Determine whether the user hasPermission view the order_waybill.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrderWayBill  $order_waybill
     * @return mixed
     */
    public function view(User $user, OrderWayBill $order_waybill)
    {
        return $user->hasPermission('view.order-waybill');
    }

    /**
     * Determine whether the user hasPermission create order_waybill.
     *
     * @param  \App\Models\OrderWayBill  $order_waybill
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.order-waybill');

    }

    /**
     * Determine whether the user hasPermission update the order_waybill.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrderWayBill  $order_waybill
     * @return mixed
     */
    public function update(User $user, OrderWayBill $order_waybill)
    {
        return $user->hasPermission('update.order-waybill');
    }
    /**
     * Determine whether the user hasPermission delete the order_waybill.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OrderWayBill  $order_waybill
     * @return mixed
     */
    public function delete(User $user, OrderWayBill $order_waybill)
    {
        return $user->hasPermission('delete.order-waybill');
    }
}
