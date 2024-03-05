<?php

namespace App\Jobs\Auction;

use App\Helpers\GuzzleRequest;
use App\Models\BidderNumber;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\Posting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeWinningBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $posting;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Posting $posting)
    {
        $this->posting = $posting;
        $this->onQueue('finalize-winning-bid-job');
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
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'json' => $this->form(),
        ];

        $request = new GuzzleRequest($form);
        $response = $request->post(env('HAMMER_TOKEN_URL') . '/api/winning-bids');

        if ($response->getStatusCode() != 200) {
            abort($response->getStatusCode(), json_encode($request->data()));
        }

    }

    private function form()
    {
        $customer = null;
        $customer_login_credential = null;
        $bidder_number = null;

        if ($this->posting && $this->posting->customer_id) {
            $customer = Customer::where('customers.customer_id', $this->posting->customer_id)
                ->first();

            $customer_login_credential = CustomerLoginCredential::where('customer_login_credentials.customer_id', $this->posting->customer_id)
                ->first();

            $bidder_number = BidderNumber::where('bidder_number_id', $this->posting->bidder_number_id)
                ->first();

        }

        $data = [
            'posting' => $this->posting->toArray(),
            'bidder_number' => $bidder_number ? $bidder_number->toArray() : null,
            'customer' => $customer ? encrypt($customer->toArray()) : null,
            'customer_login_credential' => $customer_login_credential ? encrypt($customer_login_credential->toArray()) : null,
            'signature' => sha1($this->posting->posting_id . '{' . env('API_SIGNATURE_KEY') . '}'),

        ];

        return $data;
    }
}
