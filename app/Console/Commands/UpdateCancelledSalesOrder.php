<?php

namespace App\Console\Commands;

use App\Models\Order;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Retail\CancelledSalesOrderJob;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdateCancelledSalesOrder extends Command
{
    protected $reference_code, $request, $order_postings, $order;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cancelled-sales-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Cancellation';

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
     * @return mixed
     */
    public function handle()
    {
        $current_date = now()->toDateTimeString();

        $orders = Order::select(['orders.*', 'stores.store_company_code'])
            ->joinStore()
            ->where('orders.order_status', 'Cancelled')
            ->where('orders.payment_status', 'Pending')
            ->whereNull('orders.payment_id')
            ->where('stores.store_company_code', 'HRH')
            ->whereNull('orders.payment_date')
            ->whereBetween('orders.created_at', ["2022-07-01 00:00:00", "2022-08-18 23:59:59"])
            ->get();

        $bar = new ProgressBar($this->output, count($orders));

        $bar->setFormat("Processing orders: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach ($orders as $order) {

            $date =  $order->created_at->addDays(2)->toDateTimeString();

            if ($current_date > $date) {

                $this->cancel($order);
            } else {

                continue;
            }

            $bar->advance();
        }

        $bar->finish();
        print "\n";

        $this->comment('Retail Orders Successfully Cancelled!');
    }


    /**
     * Execute Cancel order process
     *
     * @return void
     */

    public function cancel($order)
    {
        DB::transaction(function () use ($order) {
            $this->cancelOrder($order);
        });
    }

    private function cancelOrder($order)
    {
        $orders = Order::selectedFields()
            ->joinStore()
            ->where('orders.reference_code', $order->reference_code)
            ->where('stores.store_company_code', 'HRH')
            ->get();

        foreach ($orders as $order) {

            if ($order->store_company_code == 'HRH')
                dispatch(new CancelledSalesOrderJob($order->refresh()));
        }

        return $this;
    }

  
}
