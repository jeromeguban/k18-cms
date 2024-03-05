<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ads;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdsPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Ads::class;


    /**
     * Determine whether the user can unpublish the posting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ads  $ads
     * @return mixed
     */
    public function upload(User $user, Ads $ads)
    {
        return $user->hasPermission('upload.ads');
    }

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.ads');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.ads');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ads  $ads
     * @return mixed
     */
    public function update(User $user, Ads $ads)
    {
        return $user->hasPermission('update.ads');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ads  $ads
     * @return mixed
     */
    public function delete(User $user, Ads $ads)
    {
        return $user->hasPermission('delete.ad');
    }
}
