<?php 

namespace App\Processes\Hammer;

use App\Models\Hammer\Posting;
use App\Models\Hammer\Item;
use App\Models\Hammer\Customer;
use App\Models\Hammer\CustomerLoginCredential;
use Illuminate\Support\Facades\DB;

class PostingFinalizeProcess
{
	protected $posting, $customer, $request;

	/**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{
		$this->request = $request ? (object) $request : request();
        
	}

	public function create()
	{
		DB::transaction(function() {
			$this->checkIfCustomerIsExisted();
			$this->updatePostingAndItems();
		});
	}

	private function checkIfCustomerIsExisted()
    {
        if(!$this->request->posting['customer_id'])
            return $this;

        $this->customer = Customer::where('customers.hmr_customer_id', $this->request->posting['customer_id'])->first();

        // if(!$this->customer){

        // }

        return $this;    
    }

	public function updatePostingAndItems()
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


         $posting->update([ 
                'customer_id'           => $this->customer ? $this->customer->customer_id : null,
                'bid_amount'            => (float) $this->request->posting['bid_amount'] ?? null,
                'processing_fee'        => $this->request->posting['processing_fee'],
                'for_approval'          => $this->request->posting['for_approval'],
                'for_approval_status'   => $this->customer && $this->request->posting['bid_amount'] ? $this->request->posting['for_approval_status'] : null,
                'approved_date'         => $this->customer && $this->request->posting['bid_amount'] ? $this->request->posting['approved_date'] : null,
                'finalized_date'        => now()->toDateTimeString(),
                'buy_back'              => $this->request->posting['buy_back'],
            ]);

            $items->each(function($item) use ($items) {
                $item->update([ 
                    'customer_id'           => $this->customer ? $this->customer->customer_id : null,
                    'sold_amount'           => $this->customer && $this->request->posting['bid_amount'] ? (float)$this->request->posting['bid_amount'] / $items->count() : null,
                    'sold_time'             => $this->customer && $this->request->posting['bid_amount'] ? now()->toDateTimeString() : null,
                    'processing_fee'        => (float)$this->request->posting['processing_fee'] / $items->count(),
                    'for_approval'          => $this->request->posting['for_approval'],
                    'for_approval_status'   => $this->customer && $this->request->posting['bid_amount'] ? $this->request->posting['for_approval_status'] : null,
                    'approved_date'         => $this->customer && $this->request->posting['bid_amount'] ? $this->request->posting['approved_date'] : null,
                    'finalized_date'        => now()->toDateTimeString(),
                    'finalized_date'        => now()->toDateTimeString(),
                    'buy_back'              => $this->request->posting['buy_back'],
                ]);
            });

        return $this; 
	}
}