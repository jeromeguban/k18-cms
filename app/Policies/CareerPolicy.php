<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Career;
use Illuminate\Auth\Access\HandlesAuthorization;

class CareerPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Career::class;

    /**
    * Determine whether the user hasPermission list career.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.career');
    }

    /**
     * Determine whether the user hasPermission view the career.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Career  $career
     * @return mixed
     */
    public function view(User $user, Career $career)
    {
        return $user->hasPermission('view.career');
    }

    /**
     * Determine whether the user hasPermission create career.
     *
     * @param  \App\Models\Career  $career
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.career');

    }

    /**
     * Determine whether the user hasPermission update the career.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Career  $career
     * @return mixed
     */
    public function update(User $user, Career $career)
    {
        return $user->hasPermission('update.career');
    }
    /**
     * Determine whether the user hasPermission delete the career.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Career  $career
     * @return mixed
     */
    public function delete(User $user, Career $career)
    {
        return $user->hasPermission('delete.career');
    }
}
