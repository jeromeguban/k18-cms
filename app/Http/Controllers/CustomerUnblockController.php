<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Jobs\CustomerUnblockingJob;

class CustomerUnblockController extends Controller
{
/**
     * Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer )
    {
        $this->authorize('update', $customer);
        
        $customer->loginCredential->update([
            'blocked_date'  => null,
            'blocked_by'    => null
        ]);

        $this->unblockCustomerApiCallback($customer);

        activity()
            ->performedOn( $customer)
            ->withProperties($customer)
            ->log('Customer has been Unblocked');

        return [
            'success' => 1,
            'data' => $customer
        ];
    }

    private function unblockCustomerApiCallback($customer)
    {
        dispatch(new CustomerUnblockingJob($customer));

        return $this;
    }
}