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
use Illuminate\Support\Facades\DB;

class CustomerDataWarehouseDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
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
                ->deleteCustomer();
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
     * Delete customer.
     *
     * @return void
     */

    public function deleteCustomer() 
    {
        $form = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'form_params'   => [
                'hmr_reference_id'  => $this->customer->customer_id,
            ],

        ];

        $request = new GuzzleRequest($form);

        $response = $request->delete(env('DATA_WAREHOUSE_URL').'/api/customers/'.$this->customer->customer_id);

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));
    }
}
