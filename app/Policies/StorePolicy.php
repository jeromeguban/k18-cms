<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Store::class;

    /**
     * Determine whether the user can list store.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.store');
    }

    /**
     * Determine whether the user can list store.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function all(User $user)
    {
        return $user->hasPermission('all.store');
    }

    /**
     * Determine whether the user can list store.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.store');
    }

    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function view(User $user, Store $store)
    {
        return $user->hasPermission('view.store');
    }

    /**
     * Determine whether the user can create store.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.store');
    }

    /**
     * Determine whether the user can update the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function update(User $user, Store $store)
    {
        return $user->hasPermission('update.store');
    }

    /**
     * Determine whether the user can delete the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function delete(User $user, Store $store)
    {
        return $user->hasPermission('delete.store');
    }
}
