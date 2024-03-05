<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Navigation;
use Illuminate\Auth\Access\HandlesAuthorization;

class NavigationPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Navigation::class;

    /**
    * Determine whether the user hasPermission list navigation.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.navigation');
    }

    /**
     * Determine whether the user hasPermission view the navigation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Navigation  $navigation
     * @return mixed
     */
    public function view(User $user, Navigation $navigation)
    {
        return $user->hasPermission('view.navigation');
    }

    /**
     * Determine whether the user hasPermission create navigation.
     *
     * @param  \App\Models\Navigation  $navigation
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.navigation');

    }

    /**
     * Determine whether the user hasPermission update the navigation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Navigation  $navigation
     * @return mixed
     */
    public function update(User $user, Navigation $navigation)
    {
        return $user->hasPermission('update.navigation');
    }
    /**
     * Determine whether the user hasPermission delete the navigation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Navigation  $navigation
     * @return mixed
     */
    public function delete(User $user, Navigation $navigation)
    {
        return $user->hasPermission('delete.navigation');
    }
}
