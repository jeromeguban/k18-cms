<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RetailSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetailSectionPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = RetailSection::class;


    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.retail-section');
    }


    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.retail-section');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.retail-section');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RetailSection  $retail_section
     * @return mixed
     */
    public function update(User $user, RetailSection $retail_section)
    {
        return $user->hasPermission('update.retail-section');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RetailSection  $retail_section
     * @return mixed
     */
    public function delete(User $user, RetailSection $retail_section)
    {
        return $user->hasPermission('delete.retail-section');
    }
}
