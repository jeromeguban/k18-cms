<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use App\Helpers\GuzzleRequest;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomerLoginCredential;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CustomerMobileNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer, $customer_login_credential = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;

        $this->customer_login_credential = CustomerLoginCredential::where('customer_id', $this->customer->customer_id)->firstOrFail();

        // $this->onQueue('customer-mobile-number-job');
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
        $response =  $request->patch(env('HAMMER_TOKEN_URL').'/api/customer-mobile-number/'.$this->customer_login_credential->customer_id);

        if($response->getStatusCode() != 200){
            if($response->getStatusCode() == 403)
                return;
            abort($response->getStatusCode(), json_encode($request->data()));
        }
    }

    private function form(){
       
        $data = [
            'customer_login_credential' =>  $this->customer_login_credential->toArray() ?? null,
            'signature'                 =>  sha1($this->customer_login_credential->customer_id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        return $data;  
    }
}
