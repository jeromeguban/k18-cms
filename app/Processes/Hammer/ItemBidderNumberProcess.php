<?php 

namespace App\Processes\Hammer;

use App\Models\Hammer\BidderNumber;
use App\Models\Hammer\Auction;
use App\Models\Hammer\Item;
use App\Models\Hammer\Lot;
use App\Models\Hammer\Customer;
use App\Models\Hammer\Posting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ItemBidderNumberProcess
{
	protected $posting, $bidder_number = null, $customer = null;

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
            $this->getCustomer()
            	->getBidderNumber()
            	->saveBidderNumberId();
        });
    }

    public function getCustomer()
    {
    	$this->customer = Customer::where('hmr_customer_id', $this->request->posting['customer_id'])
    						->first();

    	return $this;

    }

    public function getBidderNumber()
    {
    	$this->bidder_number = BidderNumber::selectedFields()
    									->where('customer_id', $this->customer->customer_id)
    									->first();
    }

    public function saveBidderNumberId()
    {
    	$posting = Posting::where('hmr_posting_id', $this->request->posting['posting_id'])
                            ->firstOrFail();

    	$items = Item::when($posting->lot_id, function($query) use ($posting){
                            $query->where('items.lot_id', $posting->lot_id);
                            })
                            ->when($posting->item_id, function($query) use ($posting){
                                $query->where('items.item_id', $posting->item_id);
                            })
                            ->get();

         $items->each(function($item) use ($items) {
                $item->update([ 
                    'bidder_number_id'      => $this->bidder_number ? $this->bidder_number->bidder_number_id : null,
                ]);
            });

         return $this;
    }
}