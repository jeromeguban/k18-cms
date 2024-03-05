<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ModulePermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePermissionPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = ModulePermission::class;
    
    /**
    * Determine whether the user can list modulePermission.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.module-permission');
    }

    /**
     * Determine whether the user can view the modulePermission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return mixed
     */
    public function view(User $user, ModulePermission $modulePermission)
    {
        return $user->hasPermission('view.module-permission');
    }

    /**
     * Determine whether the user can create modulePermissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.module-permission');
    }

    /**
     * Determine whether the user can update the modulePermission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return mixed
     */
    public function update(User $user, ModulePermission $modulePermission)
    {
        return $user->hasPermission('update.module-permission');
    }

    /**
     * Determine whether the user can delete the modulePermission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return mixed
     */
    public function delete(User $user, ModulePermission $modulePermission)
    {
        return $user->hasPermission('delete.module-permission');
    }

    /**
     * Determine whether the user can restore the module permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return mixed
     */
    public function restore(User $user, ModulePermission $modulePermission)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the module permission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return mixed
     */
    public function forceDelete(User $user, ModulePermission $modulePermission)
    {
        //
    }
}
