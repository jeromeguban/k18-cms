<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use carbon\Carbon;
use App\Jobs\CustomerBlockingJob;

class CustomerBlockController extends Controller
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
            'blocked_date'  => Carbon::now(),
            'blocked_by'    => auth()->id()
        ]);

        $this->blockCustomerApiCallback($customer);

        activity()
            ->performedOn( $customer)
            ->withProperties($customer)
            ->log('Customer has been Blocked');

        return [
            'success' => 1,
            'data' => $customer
        ];
    }

    private function blockCustomerApiCallback($customer)
    {
         dispatch(new CustomerBlockingJob($customer));

        return $this;
    }
}
