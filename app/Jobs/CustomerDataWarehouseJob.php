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
use Illuminate\Support\Facades\DB;

class CustomerDataWarehouseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer, $customer_login_credential, $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer , CustomerLoginCredential $customer_login_credential)
    {
        $this->customer = $customer ;
        $this->customer_login_credential = $customer_login_credential ? $customer_login_credential->toArray() : null;
        // $this->address = $address ? $address->toArray() : null;

         $this->onQueue('data-warehouse');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {

            $this->createOrRetrieveToken()
                ->createCustomer();
        });
    }

    /**
     * Create or retrieve token.
     *
     * @return void
     */
    public function createOrRetrieveToken()
    {
        $form = [
            'form_params' => [
                'client'    =>  env('DATA_WAREHOUSE_CLIENT_ID'),
                'secret'    =>  env('DATA_WAREHOUSE_SECRET'),
            ]
        ];

        $request = new GuzzleRequest($form);

        $response = $request->post(env('DATA_WAREHOUSE_URL').'/auth/access-token/generate');

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), 'Authentication Error. Please try again later.');


        $this->token = $request->data()['token'];

        return $this;
    }

    /**
     * Create customer.
     *
     * @return void
     */

    public function createCustomer() 
    {
        $form = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'form_params'   => $this->form(),

        ];

        $request = new GuzzleRequest($form);

        $response = $request->post(env('DATA_WAREHOUSE_URL').'/api/customers');

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));
    }

    private function form()
    {
        $data = [
            'hmr_reference_id'           => $this->customer->customer_id,
            'customer'                  => $this->customer->toArray(),
            'customer_login_credential' => $this->customer_login_credential,
            // 'address'                   => $this->address,
            'reference'                 => 'CMS'

        ];

        return $data;
    }

}
