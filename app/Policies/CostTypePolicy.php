<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CostType;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostTypePolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = CostType::class;

    /**
     * Determine whether the user haspermission list cost types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->haspermission('list.cost-type');
    }


    /**
     * Determine whether the user haspermission create cost types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->haspermission('create.cost-type');
    }

    /**
     * Determine whether the user haspermission update the cost type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostType  $cost_type
     * @return mixed
     */
    public function update(User $user, CostType $cost_type)
    {
        return $user->haspermission('update.cost-type');
    }

    /**
     * Determine whether the user haspermission delete the cost type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostType  $cost_type
     * @return mixed
     */
    public function delete(User $user, CostType $cost_type)
    {
        return $user->haspermission('delete.cost-type');
    }

    /**
     * Determine whether the user haspermission restore the cost type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostType  $cost_type
     * @return mixed
     */
    public function restore(User $user, CostType $cost_type)
    {
        //
    }

    /**
     * Determine whether the user haspermission permanently delete the cost type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostType  $cost_type
     * @return mixed
     */
    public function forceDelete(User $user, CostType $cost_type)
    {
        //
    }
}
