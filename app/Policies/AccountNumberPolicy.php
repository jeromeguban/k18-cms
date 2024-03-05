<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AccountNumber;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountNumberPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = AccountNumber::class;

    /**
     * Determine whether the user hasPermission list account numbers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.account-number');
    }

    /**
     * Determine whether the user hasPermission view the account number.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $account_number
     * @return mixed
     */
    public function view(User $user, AccountNumber $account_number)
    {
        //
    }

    /**
     * Determine whether the user hasPermission create account numbers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.account-number');
    }

    /**
     * Determine whether the user hasPermission update the account number.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $account_number
     * @return mixed
     */
    public function update(User $user, AccountNumber $account_number)
    {
        return $user->hasPermission('update.account-number');
    }

    /**
     * Determine whether the user hasPermission delete the account number.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $account_number
     * @return mixed
     */
    public function delete(User $user, AccountNumber $account_number)
    {
        return $user->hasPermission('delete.account-number');
    }

    /**
     * Determine whether the user hasPermission restore the account number.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $account_number
     * @return mixed
     */
    public function restore(User $user, AccountNumber $account_number)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the account number.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $account_number
     * @return mixed
     */
    public function forceDelete(User $user, AccountNumber $account_number)
    {
        //
    }
}
