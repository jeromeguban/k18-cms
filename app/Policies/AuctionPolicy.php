<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Auction;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuctionPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Auction::class;


    /**
     * Determine whether the user hasPermission access the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return mixed
     */
    public function access(User $user, Auction $auction)
    {

        return $user->hasPermission('access.auction');

    }

    /**
     * Determine whether the user hasPermission list orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {

        return $user->hasPermission('list.auction');

    }

    /**
     * Determine whether the user hasPermission view the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return mixed
     */
    public function view(User $user, Auction $auction)
    {

        return $user->hasPermission('view.auction');

    }

    /**
     * Determine whether the user hasPermission update the auction.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return mixed
     */
    public function update(User $user, Auction $auction)
    {

        return $user->hasPermission('update.auction');

    }

}
