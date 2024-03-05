<?php

namespace App\Jobs\Auction;

use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use Illuminate\Bus\Queueable;
use App\Helpers\GuzzleRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer, $customer_login_credential;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, CustomerLoginCredential $customer_login_credential)
    {
       
        $this->customer = $customer;

        $this->customer_login_credential = $customer_login_credential;

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
                'Content-Type'  => 'application/json'
            ],
            'json' => $this->form(),
        ];
        
        $request = new GuzzleRequest($form);
        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/customers');
        if($response->getStatusCode() != 200){
            if($response->getStatusCode() == 403)
                return;
            abort($response->getStatusCode(), json_encode($request->data()));
        }

    }

    private function form(){
       
        $data = [
            'customer'                  =>  $this->customer->toArray(),
            'customer_login_credential' =>  $this->customer_login_credential->toArray(),
            'signature'                 => sha1($this->customer->customer_id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        return $data;  
    }
}
