<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = File::class;

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.file');
    }

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.file');
    }

    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\File  $file
     * @return mixed
     */
    public function view(User $user, File $file)
    {
        return $user->hasPermission('view.file');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.file');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\File $file
     * @return mixed
     */
    public function update(User $user, File $file)
    {
        return $user->hasPermission('update.file');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\File $file
     * @return mixed
     */
    public function delete(User $user, File $file)
    {
        return $user->hasPermission('delete.file');
    }

}
