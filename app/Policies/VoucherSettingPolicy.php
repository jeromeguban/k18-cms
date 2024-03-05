<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VoucherSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherSettingPolicy extends Policy
{
    use HandlesAuthorization;
    protected $model = VoucherSetting::class;

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $user->hasPermission('access.voucher-setting');
    }

    /**
     * Determine whether the user can list ad.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.voucher-setting');
    }


    /**
     * Determine whether the user can view the store.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherSetting  $voucher_setting
     * @return mixed
     */
    public function view(User $user, VoucherSetting $voucher_setting)
    {
        return $user->hasPermission('view.voucher-setting');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.voucher-setting');
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherSetting $voucher_setting
     * @return mixed
     */
    public function update(User $user, VoucherSetting $voucher_setting)
    {
        return $user->hasPermission('update.voucher-setting');
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherSetting $voucher_setting
     * @return mixed
     */
    public function delete(User $user, VoucherSetting $voucher_setting)
    {
        return $user->hasPermission('delete.voucher-setting');
    }
}
