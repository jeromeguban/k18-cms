<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy extends Policy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return User::isDeveloper();
    }

    protected $model = Cart::class;

    /**
     * Determine whether the user can list cart.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.cart');
    }

    /**
     * Determine whether the user can list cart.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function acces(User $user)
    {
        return $user->hasPermission('access.cart');
    }

    /**
     * Determine whether the user can view the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function view(User $user, Cart $cart)
    {
        return $user->hasPermission('view.cart');
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.cart');
    }

    /**
     * Determine whether the user can update the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function update(User $user, Cart $cart)
    {
        return $user->hasPermission('update.cart');
    }

    /**
     * Determine whether the user can delete the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function delete(User $user, Cart $cart)
    {
        return $user->hasPermission('delete.cart');
    }

    /**
     * Determine whether the user can restore the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Cart  $cart
     * @return mixed
     */
    public function restore(User $user, Cart $cart)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Cart  $cart
     * @return mixed
     */
    public function forceDelete(User $user, Cart $cart)
    {
        //
    }
}
