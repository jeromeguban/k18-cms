<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Rules\CheckIfUsernameAlreadyExist;
use App\Rules\CheckIfEmailAlreadyExist;
use App\Rules\CheckIfMobileNoExist;
use Illuminate\Support\Str;

class CustomerCredentialController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Customer $customer, CustomerLoginCredential $credential)
    {
        $this->authorize('update', $credential);

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:191',
                new CheckIfUsernameAlreadyExist
            ],
            'email' => [
                'required',
                'email',
                'max:191',
                new CheckIfEmailAlreadyExist
            ],
            'mobile_no' => [
                'required',
                'numeric',
                new CheckIfMobileNoExist
            ],
        ], [], [
            'username'  => 'User Name',
            'email'     => 'Email',
            'mobile_no' => 'Mobile Number',
        ]);

        $customer_login_credential = CustomerLoginCredential::where('customer_id', $customer->customer_id)
                                                        ->first();

        if($customer_login_credential != null) {
            $customer->loginCredential()->update([
            'email'               => encrypt(strtolower($request->email)),
            'email_index'         => md5(strtolower($request->email)),
            'username'            => encrypt(strtolower($request->username)),
            'username_index'      => md5(strtolower($request->username)),
            'mobile_no'           => encrypt($request->mobile_no),
            'mobile_no_index'     => md5($request->mobile_no),
            ]);

            
        } else {
            $customer_login_credential = CustomerLoginCredential::create([
                'customer_id'      => $customer->customer_id,
                'email'               => encrypt(strtolower($request->email)),
                'email_index'         => md5(strtolower($request->email)),
                'username'            => encrypt(strtolower($request->username)),
                'username_index'      => md5(strtolower($request->username)),
                'mobile_no'             => encrypt($request->mobile_no),
                'mobile_no_index'       => md5($request->mobile_no),
                'password'              => bcrypt($request->password),
                'confirmation_date'     => now(),
                'is_verified'           => 1,
                'registration_token'    => Str::random(25),
            ]);
        }


        activity()
                ->performedOn( $customer )
                ->withProperties($customer)
                ->log('Customer Credential has been updated');

            return [
                'success'   => 1,
                'data'      => $customer
            ];

        
    }
}
