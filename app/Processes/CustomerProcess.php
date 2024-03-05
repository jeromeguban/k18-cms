<?php

namespace App\Processes;

use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\Hammer\Customer as HammerCustomer;
use App\Models\Hammer\CustomerLoginCredential as HammerCustomerLoginCredential;
use App\Jobs\CustomerJob;
use App\Jobs\CustomerDataWarehouseJob;
use App\Jobs\CustomerDataWarehouseUpdateJob;
use App\Jobs\CustomerDataWarehouseDeleteJob;
use App\Jobs\CustomerUpdateJob;
use App\Jobs\CustomerDeleteJob;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CustomerProcess
{
	protected $request , $customer, $customer_login_credential, $address;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = ($request) ? (object) $request : request();
    }

    /**
     * Execute find process.
     *
     * @return void
    */
    public function find ($id)
    {
    	$this->customer = Customer::findOrFail($id);

        $this->address_data = Address::where('addresses.customer_id', $id)
                                    ->first();

        $this->customer_login_credential = CustomerLoginCredential::where('customer_login_credentials.customer_id', $id)
                                                                ->first();

    	return $this;
    }

    /**
     * Retrive Customer.
     *
     * @return
    */

    public function customer()
    {
    	return $this->customer;
    }

    /**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {
    	DB::transaction(function (){
    		$this->createCustomer()
    			->saveAddress();

    		if($this->request->login_credential) {
    			$this->createCustomerLoginCredential();
    		}
            $this->customerCreateApiCallback();

    	});

    	return $this;
    }

    /**
     * Execute the update process.
     *
     * @return void
     */
    public function update()
    {
    	DB::transaction(function (){
    		$this->updateCustomer()
                ->updateCustomerLoginCredential();
                if($this->request->country){
    			 $this->updateAddress();
                }
            $this->customerUpdateApiCallback();
            // $this->customerUpdateDataWarehouseCallBack();
    	});

    	return $this;
    }


    /**
     * Execute the Password Reset process.
     *
     * @return void
     */
    public function passwordReset()
    {
        DB::transaction(function () {
            $this->updatePassword();

            $this->customerUpdateApiCallback();
            // $this->customerUpdateDataWarehouseCallBack();
        });

        return $this;
    }

    /**
     * Execute the delete process.
     *
     * @return void
     */

    public function delete()
    {
    	DB::transaction(function (){
    		$this->customerDeleteApiCallback()
                ->customerDeleteDataWarehouseCallBack()
                ->deleteCustomerLoginCredential()
    			->deleteAddress()
    			->deleteCustomer();
    	});

    	return $this;
    }

    public function createCustomer()
    {
    	$this->customer = Customer::create([
    		'customer_firstname'			=> encrypt(strtoupper($this->request->customer_firstname)),
    		'customer_firstname_index'		=> md5(strtoupper($this->request->customer_firstname)),
    		'customer_lastname'       		=> encrypt(strtoupper($this->request->customer_lastname)),
            'customer_lastname_index' 		=> md5(strtoupper($this->request->customer_lastname)),
            'customer_middlename'     		=> encrypt(strtoupper($this->request->customer_middlename)),
            'customer_suffixname'     		=> encrypt(strtoupper($this->request->customer_suffixname)),
            'customer_company_name'   		=> encrypt(strtoupper($this->request->customer_company_name)),
            'customer_gender'         		=> $this->request->customer_gender,
            'customer_birthdate'      		=> Carbon::parse(request()->customer_birthday)->toDateString(),
            'customer_phone'          		=> encrypt(strtoupper($this->request->customer_phone)),
            'customer_created_by'     		=> auth()->id(),
            'customer_modified_by'    		=> auth()->id(),

    	]);

    	return $this;
    }

    public function saveAddress()
    {
    	if($this->request->country || $this->request->country != ""){
	    	$this->address = Address::create([
	    		'customer_id'				=> $this->customer->customer_id,
	    		'contact_person'			=> encrypt(strtoupper($this->request->firstname.' '.$this->request->lastname)),
	    		'contact_number'			=> encrypt($this->request->mobile_no),
	    		'country'					=> $this->request->country,
	    		'province'					=> $this->request->province,
	    		'city'						=> $this->request->city,
	    		'barangay'					=> $this->request->barangay,
	    		'zipcode'					=> $this->request->zipcode,
	    		'additional_information'	=> $this->request->additional_information,
	    		'address_created_by'     	=> auth()->id(),
                'address_modified_by'    	=> auth()->id(),
	    	]);
	    }
    	return $this;
    }

    public function createCustomerLoginCredential()
    {
    	$this->customer_login_credential = CustomerLoginCredential::create([
    		'customer_id'			=> $this->customer->customer_id,
    		'mobile_no'         	=> encrypt($this->request->mobile_no),
    		'mobile_no_index'   	=> md5($this->request->mobile_no),
    		'email'             	=> encrypt(strtolower($this->request->email)),
            'email_index'       	=> md5(strtolower($this->request->email)),
            'username'          	=> encrypt(strtolower($this->request->username)),
            'username_index'    	=> md5(strtolower($this->request->username)),
            'password'          	=> bcrypt($this->request->password),
            'confirmation_date'		=> now() ?? null,
            'is_verified'			=> 1,
            'registration_token'    => Str::random(25),
    	]);


    	return $this;
    }

    public function updateCustomer()
    {
    	$this->customer->update([
            'customer_firstname'      => encrypt(strtoupper($this->request->customer_firstname)),
            'customer_firstname_index'=> md5(strtoupper($this->request->customer_firstname)),
            'customer_lastname'       => encrypt(strtoupper($this->request->customer_lastname)),
            'customer_lastname_index' => md5(strtoupper($this->request->customer_lastname)),
            'customer_middlename'     => encrypt(strtoupper($this->request->customer_middlename)),
            'customer_suffixname'     => encrypt(strtoupper($this->request->customer_suffixname)),
            'customer_company_name'   => encrypt(strtoupper($this->request->customer_company_name)),
            'customer_gender'         => $this->request->customer_gender,
            'customer_birthdate'      => Carbon::parse(request()->customer_birthday)->toDateString(),
            'customer_phone'          => encrypt(strtoupper($this->request->customer_phone)),
            'customer_modified_by'    => auth()->id(),
        ]);


        return $this;
    }

    public function updateCustomerLoginCredential()
    {
        $this->customer_login_credential->update([
            'mobile_no'             => encrypt($this->request->mobile_no),
            'mobile_no_index'       => md5($this->request->mobile_no),
            'email'                 => encrypt(strtolower($this->request->email)),
            'email_index'           => md5(strtolower($this->request->email)),
            'username'              => encrypt(strtolower($this->request->username)),
            'username_index'        => md5(strtolower($this->request->username)),
        ]);

        return $this;
    }

    public function updateAddress()
    {
    	$this->address->update([
            'customer_id'				=> $this->customer->customer_id,
	    	'contact_person'			=> encrypt(strtoupper($this->request->firstname.' '.$this->request->lastname)),
	    	'contact_number'			=> encrypt($this->request->mobile_no),
	    	'country'					=> $this->request->country,
	    	'province'					=> $this->request->province,
	    	'city'						=> $this->request->city,
	    	'barangay'					=> $this->request->barangay,
	    	'zipcode'					=> $this->request->zipcode,
	    	'additional_information'	=> $this->request->additional_information,
            'address_modified_by'    	=> auth()->id(),
        ]);


        return $this;
    }

    public function updatePassword()
    {
        $this->customer_login_credential->update([

            'password'     => bcrypt($this->request->password),

        ]);


        return $this;
    }

    public function deleteCustomerLoginCredential()
    {
        $this->customer->loginCredential()->delete();

        return $this;
    }

    public function deleteAddress()
    {


        $this->customer->address()->delete();

        return $this;
    }

    public function deleteCustomer()
    {
        $this->customer->delete();

        return $this;
    }

    private function customerCreateApiCallback()
    {
        $customer = $this->customer;
        $customer_login_credential = $this->customer_login_credential;
        // $address = $this->address ? $this->address : null;

        dispatch(new CustomerDataWarehouseJob($customer , $customer_login_credential));

        // dispatch(new CustomerJob($customer , $customer_login_credential));

        $hammer_customer = HammerCustomer::create([
            'hmr_customer_id'               => $customer->customer_id,
            'customer_firstname'            => $customer->customer_firstname,
            'customer_firstname_index'      => $customer->customer_firstname_index,
            'customer_lastname'             => $customer->customer_lastname,
            'customer_lastname_index'       => $customer->customer_lastname_index,
            'customer_middlename'           => $customer->customer_middlename,
            'customer_suffixname'           => $customer->customer_suffixname,
            'customer_company_name'         => $customer->customer_company_name,
            'customer_gender'               => $customer->customer_gender,
            'customer_birthdate'            => $customer->customer_birthdate,
            'customer_phone'                => $customer->customer_phone,
            'sms_notification'              => 1,
            'newsletter'                    => 1,
            'currency_key'                  => 'PHP',
            'customer_created_by'           => 1,
            'customer_modified_by'          => 1,
        ]);

        $hammer_customer_login_credential = HammerCustomerLoginCredential::create([
            'customer_id'                   => $hammer_customer->customer_id,
            'mobile_no'                     => $customer_login_credential->mobile_no,
            'mobile_no_index'               => $customer_login_credential->mobile_no_index,
            'email'                         => $customer_login_credential->email,
            'email_index'                   => $customer_login_credential->email_index,
            'username'                      => $customer_login_credential->username,
            'username_index'                => $customer_login_credential->username_index,
            'password'                      => $customer_login_credential->password,
            'confirmation_date'             => $customer_login_credential->confirmation_date,
            'is_verified'                   => $customer_login_credential->is_verified,
            'registration_token'            => $customer_login_credential->registration_token
        ]);


        return $this;
    }

    private function customerUpdateApiCallback()
    {
        $address =$this->address_data ? $this->address_data : null;

        $hammer_customer = HammerCustomer::where('hmr_customer_id', $this->customer->customer_id)->first();

        $hammer_customer_login_credential = HammerCustomerLoginCredential::where('customer_id', $hammer_customer->customer_id)->first();

        dispatch(new CustomerDataWarehouseUpdateJob($this->customer->refresh(), $this->customer_login_credential->refresh()));

        $hammer_customer->update([
            'customer_firstname'            => $this->customer->customer_firstname,
            'customer_firstname_index'      => $this->customer->customer_firstname_index,
            'customer_lastname'             => $this->customer->customer_lastname,
            'customer_lastname_index'       => $this->customer->customer_lastname_index,
            'customer_middlename'           => $this->customer->customer_middlename,
            'customer_suffixname'           => $this->customer->customer_suffixname,
            'customer_company_name'         => $this->customer->customer_company_name,
            'customer_gender'               => $this->customer->customer_gender,
            'customer_birthdate'            => $this->customer->customer_birthdate,
            'customer_phone'                => $this->customer->customer_phone,
        ]);

        $hammer_customer_login_credential->update([
            'mobile_no'                     => $this->customer_login_credential->mobile_no,
            'mobile_no_index'               => $this->customer_login_credential->mobile_no_index,
            'email'                         => $this->customer_login_credential->email,
            'email_index'                   => $this->customer_login_credential->email_index,
            'username'                      => $this->customer_login_credential->username,
            'username_index'                => $this->customer_login_credential->username_index,
        ]);

        return $this;
    }

    private function customerDeleteApiCallback()
    {
        $customer = $this->customer;


        dispatch(new CustomerDataWarehouseDeleteJob($customer));

        dispatch(new CustomerDeleteJob($customer));

        return $this;
    }

}
