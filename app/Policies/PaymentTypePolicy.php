<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PaymentType;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentTypePolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = PaymentType::class;

    /**
     * Determine whether the user hasPermission list payment_type.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.payment-type');

    }

    /**
     * Determine whether the user hasPermission view the payment_type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentType  $payment_type
     * @return mixed
     */
    public function view(User $user, PaymentType $payment_type)
    {

        return $user->hasPermission('view.payment-type');

    }

    /**
     * Determine whether the user hasPermission create payment_type.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.payment-type');

    }

    /**
     * Determine whether the user hasPermission update the payment_type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentType  $payment_type
     * @return mixed
     */
    public function update(User $user, PaymentType $payment_type)
    {

        return $user->hasPermission('update.payment-type');

    }

    /**
     * Determine whether the user hasPermission delete the payment_type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentType  $payment_type
     * @return mixed
     */
    public function delete(User $user, PaymentType $payment_type)
    {

        return $user->hasPermission('delete.payment-type');

    }

    /**
     * Determine whether the user hasPermission restore the payment_type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentType  $payment_type
     * @return mixed
     */
    public function restore(User $user, PaymentType $payment_type)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the payment_type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentType  $payment_type
     * @return mixed
     */
    public function forceDelete(User $user, PaymentType $payment_type)
    {
        //
    }
}
