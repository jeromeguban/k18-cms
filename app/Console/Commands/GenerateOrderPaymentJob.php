<?php

namespace App\Console\Commands;

use App\Jobs\Retail\UpdateSalesOrderJob;
use App\Jobs\SendPaymentGatewayJob;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateOrderPaymentJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:order-payment-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Online Payment';

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

        $orders = Order::selectedFields()
                    ->join('payments','payments.id','=','orders.payment_id')
                    ->where('orders.payment_type_id','!=', 1)
                    ->where('payments.category','Auction')
                    ->get();

        $bar = new ProgressBar($this->output, count($orders));

        $bar->setFormat("Processing Orders: %current%/%max% [%bar%] %remaining%");

        $bar->start();      

        foreach($orders as $order) {

            dispatch(new SendPaymentGatewayJob($order));
            
            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Order successfully generated!');
    }

}
