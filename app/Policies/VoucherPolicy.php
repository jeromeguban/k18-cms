<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = Voucher::class;

    /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.voucher');
    }

    /**
    * Determine whether the user can list ad.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.voucher');
    }


    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Voucher  $voucher
     * @return mixed
     */
    public function view(User $user, Voucher $voucher)
    {
        return $user->hasPermission('view.voucher');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.voucher');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Voucher $voucher
     * @return mixed
     */
    public function update(User $user, Voucher $voucher)
    {
        return $user->hasPermission('update.voucher');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Voucher $voucher
     * @return mixed
     */
    public function delete(User $user, Voucher $voucher)
    {
        return $user->hasPermission('delete.voucher');
    }

}
