<?php

namespace App\Jobs;

use App\Models\BidderDeposit;
use Illuminate\Bus\Queueable;
use App\Helpers\GuzzleRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class WithdrawBidderDepositJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bidder_deposit, $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bidder_deposit)
    {
        $this->bidder_deposit = $bidder_deposit;
        // $this->onQueue('withdraw-bidder-deposit-job');
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
            'form_params' => $this->form(),
        ];
        
        $request = new GuzzleRequest($form);
        $response =  $request->patch(env('HAMMER_TOKEN_URL').'/api/bidder-deposit/'.$this->bidder_deposit->id.'/withdraw');

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));

    }

    private function form(){

        $data = [
            'status'         =>  "Withdraw",
            'signature'      =>  env('API_SIGNATURE_KEY')
        ];
        
        
        return $data;
    }
}