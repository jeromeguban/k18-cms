<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\Hammer\Customer as HammerCustomer;
use App\Models\Hammer\CustomerLoginCredential as HammerCustomerLoginCredential;

class GenerateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:customer {customer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Customer in Hammer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cms_customer = Customer::where('customers.customer_id', $this->argument('customer'))
                            ->first();

        $cms_customer_login_credential = CustomerLoginCredential::where('customer_login_credentials.customer_id', $cms_customer->customer_id)
                                                        ->first();


        $hammer_customer = HammerCustomer::where('customers.hmr_customer_id', $cms_customer->customer_id)
                                        ->first();

        if(!$hammer_customer) {
            $this->createCustomer($cms_customer, $cms_customer_login_credential);
        }
    }

    private function createCustomer($cms_customer, $cms_customer_login_credential)
    {
        $hammer_customer = HammerCustomer::create([
            'hmr_customer_id'               => $cms_customer->customer_id,
            'customer_firstname'            => $cms_customer->customer_firstname,
            'customer_firstname_index'      => $cms_customer->customer_firstname_index,
            'customer_lastname'             => $cms_customer->customer_lastname,
            'customer_lastname_index'       => $cms_customer->customer_lastname_index,
            'customer_middlename'           => $cms_customer->customer_middlename,
            'customer_suffixname'           => $cms_customer->customer_suffixname,
            'customer_company_name'         => $cms_customer->customer_company_name,
            'customer_gender'               => $cms_customer->customer_gender,
            'customer_birthdate'            => $cms_customer->customer_birthdate,
            'customer_phone'                => $cms_customer->customer_phone,
            'sms_notification'              => 1,
            'newsletter'                    => 1,
            'currency_key'                  => 'PHP',
            'customer_created_by'           => 1,
            'customer_modified_by'          => 1,
        ]);

        $hammer_customer_login_credential = HammerCustomerLoginCredential::create([
            'customer_id'                   => $hammer_customer->customer_id,
            'mobile_no'                     => $cms_customer_login_credential->mobile_no,
            'mobile_no_index'               => $cms_customer_login_credential->mobile_no_index,
            'email'                         => $cms_customer_login_credential->email,
            'email_index'                   => $cms_customer_login_credential->email_index,
            'username'                      => $cms_customer_login_credential->username,
            'username_index'                => $cms_customer_login_credential->username_index,
            'password'                      => $cms_customer_login_credential->password,
            'confirmation_date'             => $cms_customer_login_credential->confirmation_date,
            'is_verified'                   => $cms_customer_login_credential->is_verified,
            'registration_token'            => $cms_customer_login_credential->registration_token
        ]);

            
    }
}
