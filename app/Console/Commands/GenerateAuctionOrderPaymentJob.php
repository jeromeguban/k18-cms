<?php

namespace App\Console\Commands;

use App\Jobs\SendPaymentGatewayJob;
use App\Models\Order;
use Illuminate\Console\Command;

class GenerateAuctionOrderPaymentJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-order-payment-job {order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Auction Order Payment Job';

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
        $order = Order::selectedFields()
                    // ->joinStore()
                    ->where('orders.id', $this->argument('order'))
                    // ->where('stores.store_company_code', 'HASI')
                    ->firstOrFail();

        // dispatch(new SendPaymentGatewayJob($order, true));

        SendPaymentGatewayJob::dispatchSync($order, true);
    }
}
