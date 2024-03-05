<?php 

namespace App\Processes;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\BidderDeposit;
use App\Models\PaymentTransaction;
use App\Jobs\SendPaymentGatewayJob;
use Illuminate\Support\Facades\Redis;
use App\Jobs\Retail\UpdateSalesOrderJob;
use App\Jobs\Auction\BidderDepositPaymentJob;
use App\Jobs\Seller\SendSellerPaymentGatewayJob;

class PaymentGatewayCallbackProcess
{

    protected $request, $payment, $orders, $payment_type, $bidder_deposit, $type;


     /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {

        $this->request = $request ? (object) $request : request();

        $validate_reference_code = explode('-', $this->request->req_id);

        $this->type = count($validate_reference_code) == 1 ? 'order' : (count($validate_reference_code) == 2 ? 'bidder_deposit' : null);

        $this->getTransactionDetails();
    }


    /**
     * Retrive Orders.
     *
     * @return 
    */

    public function orders()
    {
        return $this->orders;
    }

    
    /**
     * Execute handle process.
     *
     * @return void
    */

    public function handle()
    {
        \DB::transaction(function(){
            switch($this->request->status) {

                case 'paid':
                    
                   $this->processTransaction();

                break;

            }
            $this->updatePaymentTransaction();
        });
    }


    private function paymentApiCallback($order)
	{	
		if($this->payment->category == 'Auction')			
			dispatch(new SendPaymentGatewayJob($order))
                // ->delay(now()->addSeconds(30))
                ;

        if($this->payment->category == 'Retail')			
			dispatch(new UpdateSalesOrderJob($order))
                // ->delay(now()->addSeconds(30))
                ;

        if ($this->payment->category == 'Seller') {
            dispatch(new SendSellerPaymentGatewayJob($order))
            // ->delay(now()->addSeconds(30))
            ;
        }
        

		return $this;
	}

    private function bidderDepositApiCallback()
	{	
		dispatch(new BidderDepositPaymentJob($this->bidder_deposit));

		return $this;
	}


    /**
     * Create Payment 
     *
     * @return $this
     */

    private function createPayment($order)
    {

       $payment_transaction =  PaymentTransaction::where('reference_code', $this->request->req_id)->first();
      
        
        $this->payment = Payment::create([
            'customer_id'           => $order->customer_id,
            'payment_gateway_reference_code' => $payment_transaction->payment_gateway_reference_code,
            'reference_code'        => $order->reference_code,
            'store_id'              => $order->store_id,
            'total_amount'          => $order->total_order_amount, 
            'total_tender_amount'   => $order->total_order_amount, 
            'remarks'               => $this->payment_type ? "Payment via {$this->payment_type->name}" : "Payment via Bux", 
            // 'payment_status'        => 'Pending',
            'category'              => $order->category,
            // 'created_by'            => 1,
            // 'modified_by'           => 1,
        ]);

        return $this;
    }

    /**
     * Create Payment Tender
     *
     * @return $this
     */
    private function updateOrder($order)
    {
        Order::where('id', $order->id)
            ->update([
                'payment_id'    => $this->payment->id,
                'order_status'  => 'Paid',
                'payment_status'=> 'Paid',
                'payment_date'  => now()->toDateTimeString(),
            ]);

        return $this;
    }


    /**
     * Mark Bidder Deposit as Paid 
     *
     * @return void
     */
    private function markBidderDepositAsPaid()
    {
        $this->bidder_deposit->update([ 
           'payment_status'     => 'Paid',
        ]);

        return $this;    
    }


    private function getPaymentType($order) {

        $this->payment_type = PaymentType::where('status', 1)
                                ->find($order->payment_type_id);

        return $this;
        
    }

    private function getTransactionDetails()
    {
        switch($this->type) {

            case 'order':

                $this->orders = Order::selectedFields()
                                    ->joinOrderPosting()
                                    ->whereNotYetPaid()
                                    ->where('orders.reference_code', $this->request->req_id)
                                    ->groupBy([
                                        'orders.id'
                                    ])
                                    ->get();

                if(!$this->orders->count())
                    abort(422, "Invalid or Paid Reference Code");

            break;

            case 'bidder_deposit': 

                $this->bidder_deposit = BidderDeposit::where('reference_code', $this->request->req_id)
                                            ->where('payment_status', 'Pending')
                                            ->where('status', 'Deposit')
                                            ->first();
                if(!$this->bidder_deposit)
                    abort(422, "Paid Reference Code");
            break;

        }
    }


    private function processTransaction()
    {

        switch($this->type) {

            case 'order':
                $this->orders->each(function($order) {
                    $this->getPaymentType($order)
                         ->createPayment($order)
                         ->updateOrder($order)
                         ->paymentApiCallback($order);

                });
            break;

            case 'bidder_deposit':
                $this->markBidderDepositAsPaid()
                    ->updateBidDepositCache()
                    ->bidderDepositApiCallback();
            break;
        }
        return $this;
    }

    private function updateBidDepositCache()
    {
        $bidder_deposit = BidderDeposit::select([
                                    \DB::raw('SUM(deposit_amount) as total_deposit_amount')
                                ])
                                ->where('customer_id', $this->bidder_deposit->customer_id)
                                ->wherePaid()
                                ->whereDeposit()
                                ->first();

        $total_bidder_deposit = $bidder_deposit ? (float) $bidder_deposit->total_deposit_amount : 0;

        Redis::set("customer:{$this->bidder_deposit->customer_id}:bid_deposit", $total_bidder_deposit);

        return $this;
    }


    /**
     * Create Payment Transaction
     *
     * @return $this
     */
    private function updatePaymentTransaction()
    {
        PaymentTransaction::where('reference_code', $this->request->req_id)
            ->update([ 
                'transaction_status'    => $this->request->status,
                // 'extras'                => str_replace("\\", "", json_encode($this->request->extras))
                'extras'                => $this->request->extras ? json_encode($this->request->extras) : null
            ]);

        return $this;
    }



}