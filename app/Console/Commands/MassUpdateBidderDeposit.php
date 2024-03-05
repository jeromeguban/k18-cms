<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BidderDeposit;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\Hammer\BidderDeposit as HammerBidderDeposit;
use App\Models\Hammer\Customer as HammerCustomer;
use App\Models\Hammer\CustomerLoginCredential as HammerCustomerLoginCredential;
use App\Models\Hammer\PaymentType as HammerPaymentType;
use Symfony\Component\Console\Helper\ProgressBar;

class MassUpdateBidderDeposit extends Command
{
    protected $hammer_customer , $hammer_payment_types;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass:update-hammer-bid-deposits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mass Hammer Update Deposits';

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
        $this->hammer_payment_types = HammerPaymentType::all();

        $cms_bidder_deposits = BidderDeposit::whereBetween('bidder_deposits.created_at', ['2022-04-01 00:00:00', '2023-02-09 23:59:59'])
                                        ->get();

        // $cms_bidder_deposits = BidderDeposit::where('bidder_deposits.id', 10)
        //                                 ->get();


        $bar = new ProgressBar($this->output, count($cms_bidder_deposits));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($cms_bidder_deposits as $index => $cms_bidder_deposit) {

            $hammer_bidder_deposit = HammerBidderDeposit::where('bidder_deposits.hmr_bidder_deposit_id', $cms_bidder_deposit->id)
                                                    ->first();

            $hammer_payment_type_id = $cms_bidder_deposit->payment_type_id ? $this->hammer_payment_types->firstWhere('hmr_payment_type_id',  $cms_bidder_deposit->payment_type_id)->id : null ;
            
            if(!$hammer_bidder_deposit) {
                $this->createBidderDeposit($cms_bidder_deposit);
                 $bar->advance();
                continue;
            }

            if(($hammer_bidder_deposit->status == 'Withdraw' && $cms_bidder_deposit->status == 'Deposit') ||  ($hammer_bidder_deposit->payment_status == 'Paid' && $cms_bidder_deposit->payment_status == 'Pending')) {
                $cms_bidder_deposit->update([
                    'status'                => $hammer_bidder_deposit->status,
                    'payment_status'        => $hammer_bidder_deposit->payment_status,
                ]);
            }


            if($hammer_bidder_deposit->status != $cms_bidder_deposit->refresh()->status || $hammer_bidder_deposit->payment_status != $cms_bidder_deposit->refresh()->payment_status || $hammer_payment_type_id != $hammer_bidder_deposit->payment_type_id ) {
                $hammer_bidder_deposit->update([
                    'status'                => $cms_bidder_deposit->status,
                    'payment_status'        => $cms_bidder_deposit->payment_status,
                    'payment_type_id'       => $hammer_payment_type_id ,
                ]);


            } 


            $bar->advance();

            

        }

        $bar->finish();
        print "\n";
            $this->comment('Bidder Deposit Successfully Updated');


    }

    private function createBidderDeposit($cms_bidder_deposit) 
    {
        $this->hammer_customer = HammerCustomer::where('customers.hmr_customer_id', $cms_bidder_deposit->customer_id)
                                        ->first();

        $hammer_payment_type = HammerPaymentType::where('payment_types.hmr_payment_type_id', $cms_bidder_deposit->payment_type_id)
                                            ->first();

        if(!$this->hammer_customer) {
            $cms_customer = Customer::where('customers.customer_id', $cms_bidder_deposit->customer_id)
                                    ->first();

            $cms_customer_login_credential = CustomerLoginCredential::where('customer_login_credentials.customer_id', $cms_bidder_deposit->customer_id)
                                    ->first();

            if($cms_customer && $cms_customer_login_credential){
                $this->createCustomer($cms_customer, $cms_customer_login_credential);
            }
        }

        if($this->hammer_customer){
            $hammer_bidder_deposit = HammerBidderDeposit::create([
                'hmr_bidder_deposit_id'    => $cms_bidder_deposit->id,
                'customer_id'              => $this->hammer_customer->refresh()->customer_id,
                'deposit_amount'           => $cms_bidder_deposit->deposit_amount,
                'status'                   => $cms_bidder_deposit->status,
                'payment_type_id'          => $hammer_payment_type->id ?? null,
                // 'deposit_type'             => $cms_bidder_deposit->deposit_type,
                'reference_code'           => $cms_bidder_deposit->reference_code,
            ]);
        }
    }

    private function createCustomer($cms_customer, $cms_customer_login_credential)
    {
        $this->hammer_customer = HammerCustomer::create([
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
            'customer_id'                   => $this->hammer_customer->customer_id,
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


        return $this;
    }

}
