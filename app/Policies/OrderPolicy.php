<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Order::class;

    /**
     * Determine whether the user hasPermission access the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function access(User $user, Order $order)
    {

        return $user->hasPermission('access.order');

    }

    /**
     * Determine whether the user hasPermission list orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {

        return $user->hasPermission('list.order');

    }

    /**
     * Determine whether the user hasPermission view the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {

        return $user->hasPermission('view.order');

    }

    /**
     * Determine whether the user hasPermission view the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $user->hasPermission('update.order');
    }
    /**
     * Determine whether the user hasPermission view the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function uncancel(User $user, Order $order)
    {
        return $user->hasPermission('uncancel.order');
    }

}
