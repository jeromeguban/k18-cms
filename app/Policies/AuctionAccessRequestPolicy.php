<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuctionAccessRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuctionAccessRequestPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = AuctionAccessRequest::class;

    /**
     * Determine whether the user can list auction access requests.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.auction-access-request');
    }

    /**
     * Determine whether the user can create auction.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.auction-access-request');
    }

    /**
     * Determine whether the user can delete the auction access request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AuctionAccessRequest  $auctionAccessRequest
     * @return mixed
     */
    public function delete(User $user, AuctionAccessRequest $auction_access_request)
    {
        return $user->hasPermission('delete.auction-access-request');
    }

    
}
