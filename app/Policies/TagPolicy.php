<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Tag::class;

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.tag');
    }

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.tag');
    }

    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function view(User $user, Tag $tag)
    {
        return $user->hasPermission('view.tag');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.tag');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag $tag
     * @return mixed
     */
    public function update(User $user, Tag $tag)
    {
        return $user->hasPermission('update.tag');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag $tag
     * @return mixed
     */
    public function delete(User $user, Tag $tag)
    {
        return $user->hasPermission('delete.tag');
    }

}
