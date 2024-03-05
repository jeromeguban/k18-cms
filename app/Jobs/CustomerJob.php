<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\GuzzleRequest;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\Address;

class CustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer, $customer_login_credential, $address , $type;

    // protected $data , $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer , $customer_login_credential)
    {
        $this->customer = $customer ;
        $this->customer_login_credential = $customer_login_credential ? $customer_login_credential->toArray() : null;
        // $this->address = $address ? $address->toArray() : null;
        // $this->onQueue('customer-job');

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

        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/customers');

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));
    }

    private function form()
    {
        $data = [
            'customer'                  => $this->customer->toArray(),
            'customer_login_credential' => $this->customer_login_credential->toArray(),
            // 'address'                   => $this->address,
            'signature'                 =>  sha1($this->customer->customer_id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        return $data;
    }
}
