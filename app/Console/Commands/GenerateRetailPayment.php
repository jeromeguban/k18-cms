<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderPosting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateRetailPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:retail-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Payment';

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
                    ->joinStore()
                    ->where('stores.store_company_code', 'HRH')
                    ->where('orders.payment_type_id', 1)
                    ->where('orders.payment_status', 'Paid')
                    ->whereNull('payment_id')
                    ->get();

        $bar = new ProgressBar($this->output, count($orders));

        $bar->setFormat("Processing Orders: %current%/%max% [%bar%] %remaining%");

        $bar->start();      

        foreach($orders as $order) {

            $order_posting = OrderPosting::select([
                                        \DB::raw('SUM((price * quantity) - discount_price) as total_amount')
                                    ])
                                    ->where('order_postings.order_id', $order->id)
                                    ->groupBy(['order_postings.order_id'])
                                    ->first();

            $payment = Payment::create([
                            'customer_id'                    => $order->customer_id,
                            'payment_gateway_reference_code' => null,
                            'reference_code'                 => $order->reference_code,
                            'store_id'                       => $order->store_id,
                            'total_amount'                   => $order_posting->total_amount,
                            'total_tender_amount'            => $order_posting->total_amount,
                            'category'                       => 'Retail',
                            'created_at'                     => $order->updated_at,
                        ]);

            $payment_order = Order::where('id', $order->id)->first();     

            $payment_order->update([
                'payment_id' => $payment->id
            ]);

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Order successfully generated a Payment!');
    }

}
