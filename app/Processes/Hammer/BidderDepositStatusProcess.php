<?php 

namespace App\Processes\Hammer;

use App\Models\Hammer\BidderDeposit;
use App\Models\Hammer\PaymentType;
use App\Models\Hammer\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BidderDepositStatusProcess
{
	protected $hammer_bidder_deposit, $hammer_payment_type, $customer;

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
    	$this->hammer_payment_type = PaymentType::where('payment_types.hmr_payment_type_id', $this->request->payment_type_id)
                            				->first();



        $this->customer = Customer::where('hmr_customer_id', $this->request->customer_id)
        						->firstOrFail();


    	DB::transaction(function() {
            $this->updateBidderDeposit();
        });
    }

    private function updateBidderDeposit()
    {
    	$this->hammer_bidder_deposit = BidderDeposit::updateOrCreate([
    		'hmr_bidder_deposit_id'	=> $this->request->id,
    		'reference_code'		=> $this->request->reference_code
    	], [
    		'deposit_amount'		=> $this->request->deposit_amount,
    		'customer_id'			=> $this->customer->customer_id,
    		'status'				=> $this->request->status,
    		'payment_type_id'		=> $this->hammer_payment_type->id,
    		'payment_status'		=> $this->request->payment_status,
    		'deposit_type'			=> $this->request->deposit_type
    	]);

    	return $this;
    }
}