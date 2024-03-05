<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payable;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayablePolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Payable::class;

    /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.payable');
    }

    /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.payable');
    }


    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payable  $payable
     * @return mixed
     */
    public function view(User $user, Payable $payable)
    {
        return $user->hasPermission('view.payable');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.payable');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payable $payable
     * @return mixed
     */
    public function update(User $user, Payable $payable)
    {
        return $user->hasPermission('update.payable');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payable $payable
     * @return mixed
     */
    public function delete(User $user, Payable $payable)
    {
        return $user->hasPermission('delete.payable');
    }

}
