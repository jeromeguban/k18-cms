<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\CustomerRegistration;
use App\Models\CustomerAccountSetting;
use App\Jobs\Auction\CustomerJob;
use App\Jobs\CustomerDataWarehouseJob;


class CustomerValidationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Update resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {

        // $this->authorize('update', CustomerRegistration::class);

        $customer_registration = CustomerRegistration::where('id', request()->id)
                                        ->whereNull('verified_date')
                                        ->first();
        if($customer_registration){

            DB::transaction(function() use ($customer_registration) {

                $customer_registration->update([
                    'verified_date' => now()->toDateTimeString(),
                ]);

                $customer = Customer::create([
                                'customer_firstname'        => $customer_registration->customer_firstname,
                                'customer_firstname_index'  => $customer_registration->customer_firstname_index,
                                'customer_lastname'         => $customer_registration->customer_lastname,
                                'customer_lastname_index'   => $customer_registration->customer_lastname_index,
                                'customer_middlename'       => $customer_registration->customer_middlename,
                                'customer_suffixname'       => $customer_registration->customer_suffixname,
                            ]);


                $customer_login_credential = CustomerLoginCredential::create([
                                                'customer_id'           => $customer->customer_id,
                                                'username'              => $customer_registration->username,
                                                'username_index'        => $customer_registration->username_index,
                                                'mobile_no'             => $customer_registration->mobile_no,
                                                'mobile_no_index'       => $customer_registration->mobile_no_index,
                                                'email'                 => $customer_registration->email,
                                                'email_index'           => $customer_registration->email_index,
                                                'registration_token'    => $customer_registration->registration_token,
                                                'password'              => $customer_registration->password,
                                                'confirmation_date'     => now()->toDateTimeString()
                                            ]);

                $customer_account_setting = CustomerAccountSetting::create([
                    'customer_id'   => $customer->customer_id,
                    'outbidded'     => 1,
                    'win'           => 1,
                    'lot_started'   => 1,
                    'newsletter'    => 1,
                ]);

                $this->apiCallback($customer, $customer_login_credential);
                $this->dataWarehouseCallBack($customer , $customer_login_credential);

            });
        }
    }

    private function apiCallback($customer, $customer_login_credential)
    {
        dispatch(new CustomerJob($customer, $customer_login_credential));

        return $this;
    }

    private function dataWarehouseCallBack($customer, $customer_login_credential)
    {
        dispatch(new CustomerDataWarehouseJob($customer, $customer_login_credential));

        return $this;
    }


}
