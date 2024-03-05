<?php

namespace App\Policies;

use App\Models\User;
use App\Models\KeyVisual;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeyVisualPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = KeyVisual::class;


    /**
     * Determine whether the user can unpublish the key_visual.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KeyVisual  $key_visual
     * @return mixed
     */
    public function upload(User $user, KeyVisual $key_visual)
    {
        return $user->hasPermission('upload.key-visual');
    }

     /**
    * Determine whether the user can list KeyVisual.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.key-visual');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.key-visual');
    }

    /**
     * Determine whether the user can update the key_visual.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KeyVisual  $key_visual
     * @return mixed
     */
    public function update(User $user, KeyVisual $key_visual)
    {
        return $user->hasPermission('update.key-visual');
    }

    /**
     * Determine whether the user can delete the key_visual.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KeyVisual  $key_visual
     * @return mixed
     */
    public function delete(User $user, KeyVisual $key_visual)
    {
        return $user->hasPermission('delete.key-visual');
    }

    /**
    * Determine whether the user can list KeyVisual.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.key-visual');
    }

}
