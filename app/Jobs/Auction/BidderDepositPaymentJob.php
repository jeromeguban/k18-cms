<?php

namespace App\Jobs\Auction;

use App\Helpers\GuzzleRequest;
use App\Models\BidderDeposit;
use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BidderDepositPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bidder_deposit, $bypass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BidderDeposit $bidder_deposit, $bypass = false)
    {
        $this->bidder_deposit = $bidder_deposit;
        $this->bypass = $bypass;
        $this->onQueue('payment-gateway');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $form = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'    => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'json' => $this->form(),
        ];
        
        $request = new GuzzleRequest($form);
        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/bidder-deposits/'.$this->bidder_deposit->reference_code.'/payments');
       
        if($response->getStatusCode() != 200){

            if($response->getStatusCode() == 403)
                return;

            abort($response->getStatusCode(), json_encode($request->data()));
            
        }

    }

    private function form(){

        $payment_transaction = PaymentTransaction::selectedFields()
                                    ->where('reference_code',  $this->bidder_deposit->reference_code)
                                    ->first()
                                    ->toArray();      


        $data = [
            'payment_transaction'   =>  $payment_transaction,
            'payment_type_id'       =>  $this->bidder_deposit->payment_type_id,
            'signature'             =>  sha1($this->bidder_deposit->id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        if($this->bypass)
            $data['bypass'] = 1;
        
        return $data;
    }
}