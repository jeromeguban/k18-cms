<?php 

namespace App\Processes;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\BidHistory;
use App\Models\BidderNumber;
use Illuminate\Http\Request;
use App\Models\Searchable\Posting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\CustomerLoginCredential;
use App\Models\Hammer\Item as HammerItem;
use App\Models\Hammer\Auction as HammerAuction;
use App\Models\Hammer\Posting as HammerPosting;
use App\Models\Hammer\Customer as HammerCustomer;
use App\Models\Hammer\BidderNumber as HammerBidderNumber;
use App\Models\Hammer\CustomerLoginCredential as HammerCustomerLoginCredential;

class SimulcastMarkAsSoldProcess
{

    protected $request, $posting, $customer, $customer_login_credential, $customer_id, 
    $bid_amount, $bidder_number, $bidder_number_id, $bidder, $hammer_bidder_numbers,
    $hammer_customer, $hammer_customer_login_credential;

     /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Posting $posting, $request = null)
    {
        $this->posting =  $posting;

        $this->request = $request ? (object) $request : request();

        $this->bid_amount = Redis::get('postings_'.$this->posting->posting_id.'_bid_amount');

        $this->customer_id = request()->customer_type == 'Floor' ? request()->customer_id : Redis::get('postings_'.$this->posting->posting_id.'_customer_id');

        $this->customer = Customer::where('customer_id', $this->customer_id)->first();

        $this->customer_login_credential = CustomerLoginCredential::where('customer_id',$this->customer_id)->first();

        $this->bidder_number_id = request()->customer_type == 'Floor' ? request()->bidder_number_id : Redis::get('postings_'.$this->posting->posting_id.'_bidder_number_id');

        $this->bidder = BidderNumber::where('bidder_number_id', $this->bidder_number_id)->first();

    }


    /**
     * Retrive Posting.
     *
     * @return 
    */

    public function posting()
    {
        return $this->posting;
    }

    
    /**
     * Execute handle process.
     *
     * @return void
    */

    public function handle()
    {
        DB::transaction(function(){
            $this->checkIfCustomerIsExisted()
                ->createBidderNumber()
                ->updatePosting()
                // ->addToCart()
                ->syncBidHistories()
                ->updateHammerPostingAndItems();

            activity()
                ->performedOn($this->posting)
                ->withProperties($this->posting)
                ->log('Posting Has been Sold');

        }, 5);

    }

       /**
     * Execute find process.
     *
     * @return void
     */

     public function find($id)
     {
 
         $this->posting = Posting::findOrFail($id);
 
         return $this;
 
     }

    private function checkIfCustomerIsExisted()
    {
     
        $this->hammer_customer = HammerCustomer::where('hmr_customer_id', $this->customer_id)->first();

        if($this->hammer_customer){
            return $this;
        }

        if(!$this->hammer_customer){
            $this->createCustomer()
                 ->createCustomerLoginCredential();
        }

        return $this;    
    }

    /**
     * Execute Create Customer
     *
     * @return void
    */
    private function createCustomer()
    {

        if(!$this->request->customer)
            abort(403, 'Invalid Customer');

        Cache::lock("hammer-customer-{$this->customer->customer_id}-create")->get(function(){

            $this->hammer_customer = HammerCustomer::create([
                'customer_firstname'        => $this->customer->customer_firstname,
                'customer_firstname_index'  => $this->customer->customer_firstname_index,
                'customer_lastname'         => $this->customer->customer_lastname,
                'customer_lastname_index'   => $this->customer->customer_lastname_index,
                'customer_middlename'       => $this->customer->customer_middlename ?? null ,
                'customer_suffixname'       => $this->customer->customer_suffixname ?? null,
                'customer_company_name'     => $this->customer->customer_company_name ?? null,
                'customer_gender'           => $this->customer->customer_gender,
                'customer_birthdate'        => $this->customer->customer_birthdate ?? null,
                'customer_phone'            => $this->customer->customer_phone ?? null,
                'customer_created_by'       => 1,
                'customer_modified_by'      => 1,
                'created_at'                => now()->toDateTimeString(),
                'updated_at'                => now()->toDateTimeString(),
                'hmr_customer_id'           => $this->customer->customer_id
            ]);

        });

        return $this;
    }

    /**
     * Execute Create Customer Login Credential
     *
     * @return void
    */
    private function createCustomerLoginCredential()
    {

        if(!$this->request->customer_login_credential)
            abort(403, 'Invalid Customer');

        Cache::lock("hammer-customer-login_credential-{$this->customer->customer_id}-create")->get(function(){
            $this->hammer_customer_login_credential = HammerCustomerLoginCredential::create([
                'customer_id'           => $this->hammer_customer->customer_id,
                'mobile_no'             => $this->customer_login_credential->mobile_no,
                'mobile_no_index'       => $this->customer_login_credential->mobile_no_index,
                'email'                 => $this->customer_login_credential->email,
                'email_index'           => $this->customer_login_credential->email_index,
                'username'              => $this->customer_login_credential->username,
                'username_index'        => $this->customer_login_credential->username_index,
                'password'              => $this->customer_login_credential->password,
                'confirmation_date'     => now()->toDateTimeString(),
                'is_verified'           => 1,
                'registration_token'    => $this->customer_login_credential->registration_token,
            ]);
        });

        return $this;
    }

    /**
     * Execute Create Bidder Number for Hammer
     *
     * @return void
    */
    public function createBidderNumber()
    {

            $hammer_auction = HammerAuction::where('hmr_auction_id', $this->posting->auction_id)->first();

            Cache::lock("hammer-auction-bidder-{$hammer_auction->auction_id}-create")->get(function() use($hammer_auction){

                if($hammer_auction){
                    $this->hammer_bidder_numbers = HammerBidderNumber::firstOrCreate([
                        'auction_id'            => $hammer_auction->auction_id,
                        'customer_id'           => $this->hammer_customer->customer_id,
                        'hmr_bidder_number_id'  => $this->bidder->bidder_number_id,
                    ],
                    [
                        'bidder_number' => $this->bidder->bidder_number,
                        'agree_date'    => now()->toDateTimeString(),
                    ]);
                }

             });
            
    	return $this;
    }
    /**
     * Execute Update Posting
     *
     * @return void
    */
    private function updatePosting()
    {
         //update Posting
         Cache::lock("posting-{$this->posting->posting_id}-update")->get(function(){
            $this->posting->update([
                'for_approval'    => request()->for_approval == 1 ? 1 : 0,
                'processing_fee'  => $this->posting->vat > 0 || $this->posting->duties > 0 ? (float) $this->bid_amount * (665 / 10000) : 0,
                'finalized_date'  => now()->toDateTimeString(),
            ]);
         });

        //refresh elastic data
        Posting::where('posting_id', $this->posting->posting_id)->searchable();  

        return $this;
    }

      /**
     * Add to Cart
     *
     * @return void
    */
    private function addToCart()
    {
         //Create Cart
         if(request()->customer_type == 'Online' && request()->for_approval == 0){
            Cache::lock("posting-cart-{$this->posting->posting_id}-create")->get(function(){
                Cart::updateOrCreate([
                    'customer_id'    => $this->customer_id,
                    'posting_id'     => $this->posting->posting_id,
                    'store_id'       => $this->posting->store_id,
                    'posting_item_id'=> null,
                ], [
                    'price'          => $this->getTotalAmount($this->posting->refresh()),
                    'quantity'       => 1,
                    'expires_at'     => $this->posting->payment_period ? Carbon::parse( $this->posting->payment_period)->toDateTimeString() : Carbon::parse( $this->posting->ending_time)->addDays(5)->toDateTimeString(),
                    'details'        => $this->posting->toJson(),
                    'category'       => 'Auction',
                ]);
            });
        }

        return $this;
    }

    private function getTotalAmount($posting)
    {
        return (float) $this->bid_amount +
        (float) $posting->processing_fee +
        (float) $posting->notarial_fee +
        (float) $posting->other_fee +
            ((float) $this->bid_amount * (float) ($posting->buyers_premium / 100)) +
            (((float) $this->bid_amount + ((float) $this->bid_amount * (float) ($posting->duties / 100))) * (float) ($posting->vat / 100)) +
            ((float) $this->bid_amount * (float) ($posting->duties / 100));
    }

     /**
     * Save Redis BidHistories Data on Database
     *
     * @return void
     */
    private function syncBidhistories()
    {   
         // Delete Bid History
         Cache::lock("posting-bid-history-{$this->posting->posting_id}-delete")->get(function(){
            BidHistory::where('posting_id',$this->posting->posting_id)->delete();
         });
        //Store Redis Bid Histories
        $bid_histories =  json_decode( Redis::get("postings_".$this->posting->posting_id."_bid_histories"));

        if($bid_histories){
            foreach($bid_histories as $bid_history){
                Cache::lock("posting-bid-history-{$this->posting->posting_id}-create")->get(function() use($bid_history){
                    BidHistory::create([
                        'posting_id'       => $bid_history->posting_id,
                        'customer_id'      => $bid_history->customer_id,
                        'bid_amount'       => $bid_history->bid_amount,
                        'bidder_number_id' => $bid_history->bidder_number_id,
                        'bid_increment'    => $bid_history->bid_increment,
                        'max_bid'          => $bid_history->max_bid,
                        'server_time'      => $bid_history->server_time
                    ]);
                });
            }
        }

        return $this;    
    }

    /**
     * Mark Item as updateHammerPostingAndItems 
     *
     * @return void
     */
    private function updateHammerPostingAndItems()
    {
           
            //Update Hammer Posting
            Cache::lock("hammer-posting-{$this->posting->posting_id}-update")->get(function(){
                HammerPosting::where('hmr_posting_id', $this->posting->posting_id)
                ->update([
                    'customer_id'         => $this->hammer_customer->customer_id,
                    'bid_amount'          => $this->bid_amount,
                    'for_approval'        => request()->for_approval == 1 ? 1 : 0,
                    'processing_fee'      => $this->posting->refresh()->processing_fee,
                    'for_approval_status' => $this->posting->for_approval_status,
                    'approved_date'       => $this->posting->approved_date,
                    'finalized_date'      => $this->posting->finalized_date,
                    'bidder_number_id'    => $this->hammer_bidder_numbers->bidder_number_id,
                    'for_approval'        => request()->for_approval == 1 ? 1 : 0,
                ]);
            });     

            //Collect Hammer Items
            $total_item_count = HammerItem::where('items.status', 'Outstanding')
                    ->when($this->posting->lot_id, function ($query){
                        $query->where('items.lot_id', $this->posting->lot_id);
                    })
                    ->when($this->posting->item_id, function ($query) {
                        $query->where('items.item_id', $this->posting->item_id);
                    })
                    ->count();
            
            Cache::lock("hammer-item-{$this->posting->posting_id}-update")->get(function() use($total_item_count){
                HammerItem::where('items.status', 'Outstanding')
                ->when($this->posting->lot_id, function ($query){
                    $query->where('items.lot_id', $this->posting->lot_id);
                })
                ->when($this->posting->item_id, function ($query) {
                    $query->where('items.item_id', $this->posting->item_id);
                })->update([ 
                    'customer_id'           => $this->hammer_customer->customer_id,
                    'sold_amount'           => (float) $this->bid_amount / $total_item_count,
                    'sold_time'             => now()->toDateTimeString(),
                    'processing_fee'        => (float) $this->posting->refresh()->processing_fee / $total_item_count,
                    'for_approval'          => request()->for_approval == 1 ? 1 : 0,
                    'for_approval_status'   => $this->posting->for_approval_status,
                    'approved_date'         => $this->posting->approved_date,
                    'finalized_date'        => now()->toDateTimeString(),
                    'bidder_number_id'      => $this->hammer_bidder_numbers->bidder_number_id,
                ]);
            }); 
           
        return $this;    
    }
  
}