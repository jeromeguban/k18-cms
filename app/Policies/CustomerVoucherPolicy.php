<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustomerVoucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerVoucherPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = CustomerVoucher::class;

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.customer-voucher');
    }

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.customer-voucher');
    }


    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerVoucher  $customer_voucher
     * @return mixed
     */
    public function view(User $user, CustomerVoucher $customer_voucher)
    {
        return $user->hasPermission('view.customer-voucher');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.customer-voucher');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerVoucher $customer_voucher
     * @return mixed
     */
    public function update(User $user, CustomerVoucher $customer_voucher)
    {
        return $user->hasPermission('update.customer-voucher');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerVoucher $customer_voucher
     * @return mixed
     */
    public function delete(User $user, CustomerVoucher $customer_voucher)
    {
        return $user->hasPermission('delete.customer-voucher');
    }
}
