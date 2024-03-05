<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Jobs\CustomerMobileNumberJob;

class CustomerValidateController extends Controller
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
            'mobile_verification_date'  => now()->toDateTimeString(),
        ]);

        $this->customerMobileNumberApiCallBack($customer);

        activity()
            ->performedOn( $customer)
            ->withProperties($customer)
            ->log('Customer has been Validated');

        return [
            'success' => 1,
            'data' => $customer
        ];
    }

    private function customerMobileNumberApiCallBack($customer)
    {
        dispatch(new CustomerMobileNumberJob($customer));

        return $this;
    }
}