<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Api;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Api::class;


     /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.api');
    }


     /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.api');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.api');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Api  $api
     * @return mixed
     */
    public function update(User $user, Api $api)
    {
        return $user->hasPermission('update.api');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Api  $api
     * @return mixed
     */
    public function delete(User $user, Api $api)
    {
        return $user->hasPermission('delete.ad');
    }

}
