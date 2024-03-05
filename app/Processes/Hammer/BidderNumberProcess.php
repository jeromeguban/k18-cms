<?php 

namespace App\Processes\Hammer;

use App\Models\Hammer\BidderNumber;
use App\Models\Hammer\Auction;
use App\Models\Hammer\Item;
use App\Models\Hammer\Lot;
use App\Models\Hammer\Customer;
use App\Models\Hammer\Posting;
use App\Models\Hammer\CustomerLoginCredential;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BidderNumberProcess
{
	protected $bidder_number, $auction = null, $customer = null;

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
     * Execute handle process.
     *
     * @return void
    */

    public function create()
    {
    	DB::transaction(function() {
            $this->checkIfCustomerExists()
            	->findAuction()
                ->createBidderNumber();
        });
    }

    public function checkIfCustomerExists()
    {
    	$this->customer = Customer::where('hmr_customer_id', $this->request->bidder_number['customer_id'])
    						->first();
    	if(!$this->customer){
    		$this->createCustomer()
    			->createCustomerLoginCredential();
    	}

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
            'customer_created_by'       => auth()->id(),
            'customer_modified_by'      => auth()->id(),
            'created_at'                => now(),
            'updated_at'                => now(),
            'hmr_customer_id'           => $this->request->customer['customer_id']
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

    public function findAuction()
    {
    	$this->auction = Auction::where('hmr_auction_id', $this->request->bidder_number['auction_id'])
    							->first();

    	return $this;
    }

    public function createBidderNumber()
    {
    	$this->bidder_number = BidderNumber::firstOrCreate([
    		'auction_id'	         => $this->auction->auction_id,
    		'customer_id'	        => $this->customer->customer_id
    	],
    	[
            'hmr_bidder_number_id'  => $this->request->bidder_number['bidder_number_id'],
    		'bidder_number'	=> $this->request->bidder_number['bidder_number'],
            'agree_date'    => now()->toDateTimeString(),
    	]);

    	return $this;
    }

    public function updateBidderNumberIdInItem()
    {
    	// if($this->auction->ending_time < now()->toDateTimeString())
    	// 	return;

    	// $lots = Lot::selectedFields()
    	// 			->where('lots.auction_id', $this->auction->auction_id)
    	// 			->get();

    	// foreach($lots as $lot){
    	// 	Item::where('items.lot_id', $lot->lot_id)
    	// 		->where('items.customer_id', $this->customer->customer_id)
    	// 		->update([
    	// 			'bidder_number_id'	=> $this->bidder_number->bidder_number_id
    	// 		]);
    	// }



    	// $lots->each(function($lot){
     //        Item::where('items.lot_id', $lot->lot_id)
     //                    ->where('items.customer_id', $this->customer->customer_id)
     //                    ->update([
     //                        'bidder_number_id'  => $this->bidder_number->bidder_number_id
     //                    ]);

     //    });

        $postings = Posting::where('postings.auction_id', $this->auction->auction_id)
                            ->where('postings.customer_id', $this->customer->customer_id)
                            ->get();

        if(!$postings) return;


        foreach($postings as $posting) {
            Item::where('items.lot_id', $posting->lot_id)
                ->update([
                    'bidder_number_id'  => $this->bidder_number->bidder_number_id
                ]);
        }

    	return $this;
    }
}