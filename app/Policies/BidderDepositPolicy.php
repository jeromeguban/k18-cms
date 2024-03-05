<?php

namespace App\Policies;

use App\Models\BidderDeposit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BidderDepositPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = BidderDeposit::class;


    /**
     * Determine whether the user hasPermission list bidder deposit.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  
        return $user->hasPermission('list.bidder-deposit');
    }

    /**
     * Determine whether the user hasPermission view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user hasPermission view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BidderDeposit  $bidder_deposit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BidderDeposit $bidder_deposit)
    {
        return $user->hasPermission('view.bidder-deposit');
    }

    /**
     * Determine whether the user hasPermission create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.bidder-deposit');
    }

    /**
     * Determine whether the user hasPermission update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BidderDeposit  $bidder_deposit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BidderDeposit $bidder_deposit)
    {
        return $user->hasPermission('update.bidder-deposit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BidderDeposit  $bidder_deposit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, BidderDeposit $bidder_deposit)
    {
        return $user->can('delete.bidder-deposit');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BidderDeposit  $bidder_deposit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, BidderDeposit $bidder_deposit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BidderDeposit  $bidder_deposit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, BidderDeposit $bidder_deposit)
    {
        //
    }
}
