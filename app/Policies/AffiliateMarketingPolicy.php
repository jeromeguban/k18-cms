<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AffiliateMarketing;
use Illuminate\Auth\Access\HandlesAuthorization;

class AffiliateMarketingPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = AffiliateMarketing::class;


     /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.affiliate-marketing');
    }


     /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.affiliate-marketing');
    }

     /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function view(User $user)
    {
        return $user->hasPermission('view.affiliate-marketing');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.affiliate-marketing');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AffiliateMarketing  $affiliate_marketing
     * @return mixed
     */
    public function update(User $user, AffiliateMarketing $affiliate_marketing)
    {
        return $user->hasPermission('update.affiliate-marketing');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AffiliateMarketing  $affiliate_marketing
     * @return mixed
     */
    public function delete(User $user, AffiliateMarketing $affiliate_marketing)
    {
        return $user->hasPermission('delete.affiliate-marketing');
    }

}
