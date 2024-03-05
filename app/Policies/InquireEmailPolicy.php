<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InquireEmail;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquireEmailPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = InquireEmail::class;

    /**
    * Determine whether the user hasPermission list inquire_email.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.inquire_email');
    }
    /**
     * Determine whether the user hasPermission view the inquire_email.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InquireEmail  $inquire_email
     * @return mixed
     */
    public function view(User $user, InquireEmail $inquire_email)
    {
        return $user->hasPermission('view.inquire_email');
    }

    /**
     * Determine whether the user hasPermission create brands.
     *
     * @param  \App\Models\InquireEmail  $inquire_email
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.inquire_email');

    }

    /**
     * Determine whether the user hasPermission update the inquire_email.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InquireEmail  $inquire_email
     * @return mixed
     */
    public function update(User $user, InquireEmail $inquire_email)
    {
        return $user->hasPermission('update.inquire_email');
    }
    /**
     * Determine whether the user hasPermission delete the inquire_email.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InquireEmail  $inquire_email
     * @return mixed
     */
    public function delete(User $user, InquireEmail $inquire_email)
    {
        return $user->hasPermission('delete.inquire_email');
    }
}
