<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquiryPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Inquiry::class;

    /**
     * Determine whether the user hasPermission list Inquiry.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.Inquiry');
    }
    /**
     * Determine whether the user hasPermission view the Inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inquiry  $Inquiry
     * @return mixed
     */
    public function view(User $user, Inquiry $Inquiry)
    {
        return $user->hasPermission('view.Inquiry');
    }
    /**
     * Determine whether the user hasPermission delete the Inquiry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inquiry  $Inquiry
     * @return mixed
     */
    public function delete(User $user, Inquiry $Inquiry)
    {
        return $user->hasPermission('delete.Inquiry');
    }
}
