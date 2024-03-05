<?php

namespace App\Policies;

use App\Models\Posting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostingPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Posting::class;

    /**
     * Determine whether the user hasPermission list posting.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.posting');
    }
    /**
     * Determine whether the user hasPermission view the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function view(User $user, Posting $posting)
    {
        return $user->hasPermission('view.posting');
    }

    /**
     * Determine whether the user hasPermission create brands.
     *
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.posting');

    }

    /**
     * Determine whether the user hasPermission update the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function update(User $user, Posting $posting)
    {
        return $user->hasPermission('update.posting');
    }
    /**
     * Determine whether the user hasPermission delete the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function delete(User $user, Posting $posting)
    {
        return $user->hasPermission('delete.posting');
    }
    /**
     * Determine whether the user hasPermission restore the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Posting  $posting
     * @return mixed
     */

    /**
     * Determine whether the user hasPermission publish the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function publish(User $user, Posting $posting)
    {
        return $user->hasPermission('publish.posting');
    }
    /**
     * Determine whether the user hasPermission unpublish the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function unpublish(User $user, Posting $posting)
    {
        return $user->hasPermission('unpublish.posting');
    }

    /**
     * Determine whether the user hasPermission unpublish the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function inventoryRemoval(User $user, Posting $posting)
    {
        return $user->hasPermission('inventory-removal.posting');
    }
}
