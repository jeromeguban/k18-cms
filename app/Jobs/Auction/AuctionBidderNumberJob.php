<?php

namespace App\Jobs\Auction;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\BidderNumber;
use App\Processes\Hammer\BidderNumberProcess;
use App\Helpers\GuzzleRequest;

class AuctionBidderNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bidder_number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BidderNumber $bidder_number)
    {
        $this->bidder_number = $bidder_number;

        // $this->onQueue('auction-bidder-number-job');

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = new BidderNumberProcess($this->form());
        $process->create();
    }

    private function form()
    {
        $customer = Customer::selectedFields()
                            // ->joinLoginCredential()
                            ->where('customers.customer_id', $this->bidder_number->customer_id)
                            ->first();

        $customer_login_credential = CustomerLoginCredential::where('customer_id', $customer->customer_id)
                                                            ->first();


        $data =  [
            'customer'      => $customer->toArray(),
            'customer_login_credential' => $customer_login_credential->toArray(),
            'bidder_number' => $this->bidder_number->toArray(),
            'signature'     => sha1($this->bidder_number->bidder_number_id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        return $data;
    }
}
