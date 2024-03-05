<?php

namespace App\Observers;

use App\Models\PaymentType;
use App\Models\Searchable\PaymentType as SearchablePaymentType;

class PaymentTypeObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;


    /**
     * Handle the PaymentType "created" event.
     *
     * @param  \App\Models\PaymentType  $payment_type
     * @return void
     */
    public function created(PaymentType $payment_type)
    {
        SearchablePaymentType::where('id', $payment_type->id)->searchable();
    }

    /**
     * Handle the PaymentType "updated" event.
     *
     * @param  \App\Models\PaymentType  $payment_type
     * @return void
     */
    public function updated(PaymentType $payment_type)
    {
        SearchablePaymentType::where('id', $payment_type->id)->searchable();
    }

    /**
     * Handle the PaymentType "deleted" event.
     *
     * @param  \App\Models\PaymentType  $payment_type
     * @return void
     */
    public function deleted(PaymentType $payment_type)
    {

        SearchablePaymentType::where('id', $payment_type->id)->onlyTrashed()->unsearchable();
    }

    /**
     * Handle the PaymentType "active" event.
     *
     * @param  \App\Models\PaymentType  $payment_type
     * @return void
     */
    public function active(PaymentType $payment_type)
    {
        SearchablePaymentType::where('id', $payment_type->id)->searchable();
    }
}
