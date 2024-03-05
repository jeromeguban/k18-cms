<?php

namespace App\Jobs;

use App\Helpers\GuzzleRequest;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CustomerBlockingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer, $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        // $this->onQueue('customer-blocking');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = new GuzzleRequest([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'    => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'json' => [
                'signature' => sha1($this->customer->customer_id.'{'.env('API_SIGNATURE_KEY').'}')
            ]
        ]);

        $response =  $request->patch(env('HAMMER_TOKEN_URL').'/api/customers/'. $this->customer->customer_id.'/block');

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));
    }
}
