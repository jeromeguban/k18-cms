<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuickLink;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuickLinkPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = QuickLink::class;

   
    /**
     * Determine whether the user can unpublish the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuickLink  $posting
     * @return mixed
     */
    public function upload(User $user, QuickLink $quicklink)
    {
        return $user->hasPermission('access.quicklink');
    }

    /**
    * Determine whether the user can list quicklink.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.quicklink');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.quickLink');
    }

    /**
     * Determine whether the user can update the quicklink.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quicklink  $quicklink
     * @return mixed
     */
    public function update(User $user, Quicklink $quicklink)
    {
        return $user->hasPermission('update.quicklink');
    }

    /**
     * Determine whether the user can delete the quicklink.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quicklink  $quicklink
     * @return mixed
     */
    public function delete(User $user, Quicklink $quicklink)
    {
        return $user->hasPermission('delete.quicklink');
    }

}
