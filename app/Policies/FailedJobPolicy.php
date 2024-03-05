<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FailedJob;
use Illuminate\Auth\Access\HandlesAuthorization;

class FailedJobPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = FailedJob::class;

    /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.failed-job');
    }

    /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.failed-job');
    }


    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FailedJob  $failed_job
     * @return mixed
     */
    public function view(User $user, FailedJob $failed_job)
    {
        return $user->hasPermission('view.failed-job');
    }

}
