<?php

namespace App\Policies;

use App\Models\AbandonedCart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbandonedCartPolicy extends Policy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return User::isDeveloper();
    }

    protected $model = AbandonedCart::class;

    /**
     * Determine whether the user can list abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.abandoned-cart');
    }

    /**
     * Determine whether the user can list abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function acces(User $user)
    {
        return $user->hasPermission('access.abandoned-cart');
    }

    /**
     * Determine whether the user can view the abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AbandonedCart  $abandoned_cart
     * @return mixed
     */
    public function view(User $user, AbandonedCart $abandoned_cart)
    {
        return $user->hasPermission('view.abandoned-cart');
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.abandoned-cart');
    }

    /**
     * Determine whether the user can update the abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AbandonedCart  $abandoned_cart
     * @return mixed
     */
    public function update(User $user, AbandonedCart $abandoned_cart)
    {
        return $user->hasPermission('update.abandoned-cart');
    }

    /**
     * Determine whether the user can delete the abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AbandonedCart  $abandoned_cart
     * @return mixed
     */
    public function delete(User $user, AbandonedCart $abandoned_cart)
    {
        return $user->hasPermission('delete.abandoned-cart');
    }

    /**
     * Determine whether the user can restore the abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\AbandonedCart  $abandoned_cart
     * @return mixed
     */
    public function restore(User $user, AbandonedCart $abandoned_cart)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the abandoned-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\AbandonedCart  $abandoned_cart
     * @return mixed
     */
    public function forceDelete(User $user, AbandonedCart $abandoned_cart)
    {
        //
    }
}
