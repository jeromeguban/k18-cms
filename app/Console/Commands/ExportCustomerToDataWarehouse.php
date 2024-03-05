<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\CustomerLoginCredential;
use App\Models\Address;
use App\Jobs\Auction\CustomerDataWarehouseJob;
use Symfony\Component\Console\Helper\ProgressBar;

class ExportCustomerToDataWarehouse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:customer-to-datawarehouse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Customer to DataWarehouse';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers = Customer::whereBetween('customer_id', [69268 , 75389])
                            ->whereNull('deleted_at')
                            ->get();

        $this->customer_bar = new ProgressBar($this->output, count($customers));

        $this->customer_bar->setFormat("Processing customers: %current%/%max% [%bar%] %remaining%");

        $this->customer_bar->start();

        foreach($customers as $customer) {
            dispatch(new CustomerDataWarehouseJob($customer));

            $this->customer_bar->advance();
        }
        
        $this->customer_bar->finish();
        print "\n";
        return $this->comment('Customer Export successfully!');
    }
}
