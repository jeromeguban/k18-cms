<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bank;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Bank::class;

    /**
     * Determine whether the user hasPermission list bank.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.bank');
    }

    /**
     * Determine whether the user hasPermission view the bank.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $bank
     * @return mixed
     */
    public function view(User $user, Bank $bank)
    {
        return $user->hasPermission('view.bank');
    }

    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.bank');
    }

    /**
     * Determine whether the user hasPermission update the bank.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $bank
     * @return mixed
     */
    public function update(User $user, Bank $bank)
    {
        return $user->hasPermission('update.bank');
    }

    /**
     * Determine whether the user hasPermission delete the bank.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $bank
     * @return mixed
     */
    public function delete(User $user, Bank $bank)
    {
        return $user->hasPermission('delete.bank');
    }

    /**
     * Determine whether the user hasPermission restore the bank.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Bank  $bank
     * @return mixed
     */
    public function restore(User $user, Bank $bank)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the bank.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Bank  $bank
     * @return mixed
     */
    public function forceDelete(User $user, Bank $bank)
    {
        //
    }
}
