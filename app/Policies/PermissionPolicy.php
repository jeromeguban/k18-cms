<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Permission::class;
    
    /**
    * Determine whether the user hasPermission list permission.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.permission');
    }

    /**
     * Determine whether the user hasPermission view the permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission)
    {
        return $user->hasPermission('view.permission');
    }

    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.permission');
    }

    /**
     * Determine whether the user hasPermission update the permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return mixed
     */
    public function update(User $user, Permission $permission)
    {
        return $user->hasPermission('update.permission');
    }

    /**
     * Determine whether the user hasPermission delete the permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission)
    {
        return $user->hasPermission('delete.permission');
    }

    /**
     * Determine whether the user hasPermission restore the permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Permission  $permission
     * @return mixed
     */
    public function restore(User $user, Permission $permission)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Permission  $permission
     * @return mixed
     */
    public function forceDelete(User $user, Permission $permission)
    {
        //
    }
}
