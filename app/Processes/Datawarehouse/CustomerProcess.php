<?php 

namespace App\Processes\Datawarehouse;

use App\Models\Datawarehouse\Address;
use App\Models\Datawarehouse\Customer;
use App\Models\Datawarehouse\CustomerLoginCredential;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerProcess
{
	protected $customer;

	/**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{
		$this->request = $request ? (object) $request : request();
        
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
    			->createCustomerLoginCredential();

    		// if($this->request->address) {
    		// 	$this->createAddress();
    		// }

    	});

    	return $this;
    }

    private function createCustomer()
    {
    	$this->customer = Customer::create([
            'customer_firstname'        => $this->request->customer["customer_firstname"],
            'customer_firstname_index'  => $this->request->customer['customer_firstname_index'],
            'customer_lastname'         => $this->request->customer['customer_lastname'],
            'customer_lastname_index'   => $this->request->customer['customer_lastname_index'],
            'customer_middlename'       => $this->request->customer['customer_middlename'] ?? null ,
            'customer_suffixname'       => $this->request->customer['customer_suffixname'] ?? null,
            'customer_company_name'     => $this->request->customer['customer_company_name'] ?? null,
            'customer_gender'           => $this->request->customer['customer_gender'],
            'customer_birthdate'        => $this->request->customer['customer_birthdate'] ?? null,
            'customer_phone'            => $this->request->customer['customer_phone'] ?? null,
            // 'customer_created_by'       => auth()->id(),
            // 'customer_modified_by'      => auth()->id(),
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        return $this;
    }

    private function createCustomerLoginCredential()
    {
    	CustomerLoginCredential::create([
            'customer_id'           => $this->customer->customer_id,
            'mobile_no'             => $this->request->customer_login_credential['mobile_no'],
            'mobile_no_index'       => $this->request->customer_login_credential['mobile_no_index'],
            'email'                 => $this->request->customer_login_credential['email'],
            'email_index'           => $this->request->customer_login_credential['email_index'],
            'username'              => $this->request->customer_login_credential['username'],
            'username_index'        => $this->request->customer_login_credential['username_index'],
            'password'              => $this->request->customer_login_credential['password'],
            'confirmation_date'     => now(),
            'is_verified'           => 1,
            'registration_token'    => $this->request->customer_login_credential['registration_token'],
        ]);

        return $this;
    }

    private function createAddress()
    {
    	Address::create([
                    'customer_id'               => $this->request->address['customer_id'],
                    'contact_person'            => $this->request->address['contact_person'],
                    'contact_number'            => $this->request->address['country'],
                    'country'                   => $this->request->address['country'],
                    'province'                  => $this->request->address['province'],
                    'city'                      => $this->request->address['city'],
                    'barangay'                  => $this->request->address['barangay'],
                    'zipcode'                   => $this->request->address['zipcode'],
                    'additional_information'    => $this->request->address['additional_information'] ?? null,
                    // 'address_created_by'        => auth()->id(),
                    // 'address_modified_by'       => auth()->id()
            ]);

        return $this;
    }
}