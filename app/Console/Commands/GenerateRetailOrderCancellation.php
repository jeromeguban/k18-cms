<?php

namespace App\Console\Commands;

use App\Jobs\Retail\CancelledSalesOrderJob;
use App\Models\Order;
use App\Models\OrderPosting;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateRetailOrderCancellation extends Command
{
    protected $reference_code, $request, $order_postings, $order;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:retail-order-cancellation';

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
            ->where('orders.order_status', 'Pending')
            ->where('orders.payment_status', 'Pending')
            ->whereNull('orders.payment_id')
            ->where('stores.store_company_code', 'HRH')
            ->whereNull('orders.cancelled_date')
            ->whereNull('orders.payment_date')
            ->whereBetween('orders.created_at', ["2022-09-12 00:00:00", $current_date])
            ->get();

        $bar = new ProgressBar($this->output, count($orders));

        $bar->setFormat("Processing orders: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach ($orders as $order) {

            $date = $order->created_at->addDays(2)->toDateTimeString();

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
            $this->retrieveOrderPostings($order)
                ->addCheckoutQtyToItemRemainingQty($order)
                ->cancelOrderPosting($order)
                ->cancelOrder($order);
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

            $order->update([
                'cancelled_date' => now()->toDateTimeString(),
                'order_status' => 'Cancelled',
            ]);

            dispatch(new CancelledSalesOrderJob($order->refresh()));
        }

        return $this;
    }

    private function cancelOrderPosting($order)
    {
        OrderPosting::where('reference_code', $order->reference_code)
            ->update([
                'cancelled_date' => now()->toDateTimeString(),
                'cancelled_by' => 1,
            ]);

        return $this;
    }

    private function addCheckoutQtyToItemRemainingQty($order)
    {

        foreach ($this->order_postings->where('category', 'Retail') as $order_posting) {

            $item = PostingItem::where('posting_id', $order_posting->posting_id)
                ->where('id', $order_posting->posting_item_id)
                ->first();

            $item->update([
                'quantity' => (int) $item->quantity + (int) $order_posting->quantity,
            ]);

            SearchablePostingItem::where('id', $item->id)
                ->searchable();

            $total_posting_items = PostingItem::select([
                DB::raw('SUM(posting_items.quantity) AS total_quantity'),
            ])
                ->where('posting_items.posting_id', $item->posting_id)
                ->groupBy([
                    'posting_items.posting_id',
                ])
                ->first();

            if (!$total_posting_items) {
                continue;
            }

            Posting::where('posting_id', $item->posting_id)
                ->update([
                    'quantity' => $total_posting_items->total_quantity,
                ]);

            SearchablePosting::where('posting_id', $item->posting_id)
                ->searchable();
        }

        return $this;
    }

    private function retrieveOrderPostings($order)
    {

        $this->order_postings = OrderPosting::where('reference_code', $order->reference_code)
            ->get();

        return $this;
    }
}
