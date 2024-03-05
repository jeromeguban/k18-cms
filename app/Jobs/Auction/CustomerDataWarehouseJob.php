<?php

namespace App\Jobs\Auction;

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
use App\Processes\Datawarehouse\CustomerProcess;
use Illuminate\Support\Facades\DB;

class CustomerDataWarehouseJob implements ShouldQueue
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
        // $this->onQueue('customer-data-warehouse-job');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = new CustomerProcess($this->form());
        $process->create();
    }

 
    private function form()
    {
        $customer_login_credential = CustomerLoginCredential::where('customer_id', $this->customer->customer_id)
                                                            ->first();

        $address = Address::where('customer_id', $this->customer->customer_id)
                        ->first();

        $data = [
            'customer'                  => $this->customer->toArray(),
            'customer_login_credential' => $customer_login_credential->toArray(),
            // 'address'                   => $address ? $address->toArray() : null,
            'reference'                 => 'CMS'

        ];

        return $data;
    }

}
