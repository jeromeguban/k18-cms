<?php

namespace App\Policies;

use App\Models\AccountExecutive;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountExecutivePolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = AccountExecutive::class;

    /**
     * Determine whether the user hasPermission list account executives.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return $user->hasPermission('list.account-executive');
    }

    /**
     * Determine whether the user hasPermission view the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountExecutive  $accountExecutive
     * @return mixed
     */
    public function view(User $user, AccountExecutive $accountExecutive)
    {
        return $user->hasPermission('view.account-executive');
    }

    /**
     * Determine whether the user hasPermission create account executives.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.account-executive');
    }

    /**
     * Determine whether the user hasPermission update the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountExecutive  $accountExecutive
     * @return mixed
     */
    public function update(User $user, AccountExecutive $accountExecutive)
    {
        return $user->hasPermission('update.account-executive');
    }

    /**
     * Determine whether the user hasPermission delete the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountExecutive  $accountExecutive
     * @return mixed
     */
    public function delete(User $user, AccountExecutive $accountExecutive)
    {
        return $user->hasPermission('delete.account-executive');
    }

    /**
     * Determine whether the user hasPermission restore the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountExecutive  $accountExecutive
     * @return mixed
     */
    public function restore(User $user, AccountExecutive $accountExecutive)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountExecutive  $accountExecutive
     * @return mixed
     */
    public function forceDelete(User $user, AccountExecutive $accountExecutive)
    {
        //
    }

    /**
     * Determine whether the user hasPermission view the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountExecutive  $accountExecutive
     * @return mixed
     */
    public function accountExecutiveReports(User $user, AccountExecutive $accountExecutive)
    {
        return $user->hasPermission('account-executive-reports.account-executive');
    }
}
