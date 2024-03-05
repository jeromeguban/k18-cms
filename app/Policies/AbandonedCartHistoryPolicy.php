<?php

namespace App\Policies;

use App\Models\AbandonedCartHistory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbandonedCartHistoryPolicy extends Policy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return User::isDeveloper();
    }

    protected $model = AbandonedCartHistory::class;

    /**
     * Determine whether the user can list abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.abandoned-cart-history');
    }

    /**
     * Determine whether the user can list abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function acces(User $user)
    {
        return $user->hasPermission('access.abandoned-cart-history');
    }

    /**
     * Determine whether the user can view the abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AbandonedCartHistory  $abandoned_cart_history
     * @return mixed
     */
    public function view(User $user, AbandonedCartHistory $abandoned_cart_history)
    {
        return $user->hasPermission('view.abandoned-cart-history');
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.abandoned-cart-history');
    }

    /**
     * Determine whether the user can update the abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AbandonedCartHistory  $abandoned_cart_history
     * @return mixed
     */
    public function update(User $user, AbandonedCartHistory $abandoned_cart_history)
    {
        return $user->hasPermission('update.abandoned-cart-history');
    }

    /**
     * Determine whether the user can delete the abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AbandonedCartHistory  $abandoned_cart_history
     * @return mixed
     */
    public function delete(User $user, AbandonedCartHistory $abandoned_cart_history)
    {
        return $user->hasPermission('delete.abandoned-cart-history');
    }

    /**
     * Determine whether the user can restore the abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\AbandonedCartHistory  $abandoned_cart_history
     * @return mixed
     */
    public function restore(User $user, AbandonedCartHistory $abandoned_cart_history)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the abandoned-cart-history.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\AbandonedCartHistory  $abandoned_cart_history
     * @return mixed
     */
    public function forceDelete(User $user, AbandonedCartHistory $abandoned_cart_history)
    {
        //
    }

}
