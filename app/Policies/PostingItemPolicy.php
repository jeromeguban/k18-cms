<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PostingItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostingItemPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = PostingItem::class;

    /**
    * Determine whether the user hasPermission list posting.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.posting-item');
    }
    /**
     * Determine whether the user hasPermission view the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function view(User $user, PostingItem $posting_item)
    {
        return $user->hasPermission('view.posting-item');
    }

    /**
     * Determine whether the user hasPermission update the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function update(User $user, PostingItem $posting_item)
    {
        return $user->hasPermission('update.posting-item');
    }
    /**
     * Determine whether the user hasPermission delete the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function delete(User $user, PostingItem $posting_item)
    {
        return $user->hasPermission('delete.posting-item');
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
    public function publish(User $user, PostingItem $posting_item)
    {
        return $user->hasPermission('publish.posting-item');
    }
    /**
     * Determine whether the user hasPermission unpublish the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posting  $posting
     * @return mixed
     */
    public function unpublish(User $user, PostingItem $posting_item)
    {
        return $user->hasPermission('unpublish.posting-item');
    }
}
