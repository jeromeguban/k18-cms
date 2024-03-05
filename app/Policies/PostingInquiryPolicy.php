<?php

namespace App\Policies;

use App\Models\PostingInquiry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostingInquiryPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = PostingInquiry::class;

    /**
     * Determine whether the user can list posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.posting-inquiry');
    }

    /**
     * Determine whether the user can view the posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostingInquiry  $posting_inquiry
     * @return mixed
     */
    public function view(User $user, PostingInquiry $posting_inquiry)
    {
        return $user->hasPermission('view.posting-inquiry');
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.posting-inquiry');
    }

    /**
     * Determine whether the user can update the posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostingInquiry  $posting_inquiry
     * @return mixed
     */
    public function update(User $user, PostingInquiry $posting_inquiry)
    {
        return $user->hasPermission('update.posting-inquiry');
    }

    /**
     * Determine whether the user can delete the posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostingInquiry  $posting_inquiry
     * @return mixed
     */
    public function delete(User $user, PostingInquiry $posting_inquiry)
    {
        return $user->hasPermission('delete.posting-inquiry');
    }

    /**
     * Determine whether the user can restore the posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\PostingInquiry  $posting_inquiry
     * @return mixed
     */
    public function restore(User $user, PostingInquiry $posting_inquiry)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\PostingInquiry  $posting_inquiry
     * @return mixed
     */
    public function forceDelete(User $user, PostingInquiry $posting_inquiry)
    {
        //
    }

    /**
     * Determine whether the user can view the posting_inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostingInquiry  $posting_inquiry
     * @return mixed
     */
    public function admin(User $user, PostingInquiry $posting_inquiry)
    {
        return $user->hasPermission('admin.posting-inquiry');
    }
}
