<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cost;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Cost::class;

    /**
     * Determine whether the user hasPermission list costs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.cost');
    }

    /**
     * Determine whether the user hasPermission view the cost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cost  $cost
     * @return mixed
     */
    public function view(User $user, Cost $cost)
    {
        return $user->hasPermission('view.cost');
    }

    /**
     * Determine whether the user hasPermission create costs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.cost');
    }

    /**
     * Determine whether the user hasPermission update the cost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cost  $cost
     * @return mixed
     */
    public function update(User $user, Cost $cost)
    {
        return $user->hasPermission('update.cost');
    }

    /**
     * Determine whether the user hasPermission delete the cost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cost  $cost
     * @return mixed
     */
    public function delete(User $user, Cost $cost)
    {
        return $user->hasPermission('delete.cost');
    }

    /**
     * Determine whether the user hasPermission restore the cost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cost  $cost
     * @return mixed
     */
    public function restore(User $user, Cost $cost)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the cost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cost  $cost
     * @return mixed
     */
    public function forceDelete(User $user, Cost $cost)
    {
        //
    }
}
