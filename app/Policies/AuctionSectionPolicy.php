<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuctionSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuctionSectionPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = AuctionSection::class;


    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.auction-section');
    }


    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.auction-section');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.auction-section');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AuctionSection  $auction_section
     * @return mixed
     */
    public function update(User $user, AuctionSection $auction_section)
    {
        return $user->hasPermission('update.auction-section');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AuctionSection  $auction_section
     * @return mixed
     */
    public function delete(User $user, AuctionSection $auction_section)
    {
        return $user->hasPermission('delete.auction-section');
    }
}
