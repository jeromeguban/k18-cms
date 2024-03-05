<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EventAccessRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventAccessRequestPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = EventAccessRequest::class;

    /**
     * Determine whether the user can list auction access requests.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.event-access-request');
    }

    /**
     * Determine whether the user can create auction.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.event-access-request');
    }

     /**
     * Determine whether the user can create auction.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function approve(User $user)
    {
        return $user->hasPermission('approve.event-access-request');
    }

     /**
     * Determine whether the user can create auction.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function decline(User $user)
    {
        return $user->hasPermission('decline.event-access-request');
    }

    /**
     * Determine whether the user can delete the auction access request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventAccessRequest  $auctionAccessRequest
     * @return mixed
     */
    public function delete(User $user, EventAccessRequest $event_access_request)
    {
        return $user->hasPermission('delete.auction-access-request');
    }

    
}
