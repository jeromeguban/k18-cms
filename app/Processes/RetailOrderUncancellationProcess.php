<?php

namespace App\Processes;

use App\Jobs\Retail\OrderUncancellationJob;
use App\Models\Order;
use App\Models\OrderPosting;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use Illuminate\Support\Facades\DB;

class RetailOrderUncancellationProcess
{
    protected $order;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = $request ? (object) $request : request();
    }

    /**
     * Execute find process.
     *
     * @return void
     */

    public function find($id)
    {

        $this->order = Order::findOrFail($id);
        return $this;

    }

    /**
     * Execute the upload process.
     *
     * @return void
     */
    public function uncancel()
    {
        DB::transaction(function () {
            $this->retrieveOrderPostings($this->order)
                ->lessCheckoutQtyToItemRemainingQty($this->order)
                ->uncancelOrderPosting($this->order)
                ->uncancelOrder($this->order);
        });

        return $this;
    }

    private function uncancelOrder($order)
    {
        $orders = Order::selectedFields()
            ->joinStore()
            ->where('orders.reference_code', $order->reference_code)
            ->where('stores.store_company_code', 'HRH')
            ->get();

        foreach ($orders as $order) {

            $order->update([
                'cancelled_date' => null,
                'order_status' => 'Pending',
            ]);

            dispatch(new OrderUncancellationJob($order->refresh()));
        }

        return $this;
    }

    private function uncancelOrderPosting($order)
    {
        OrderPosting::where('reference_code', $order->reference_code)
            ->update([
                'cancelled_date' => null,
                'cancelled_by' => 1,
            ]);

        return $this;
    }

    private function lessCheckoutQtyToItemRemainingQty($order)
    {

        foreach ($this->order_postings->where('category', 'Retail') as $order_posting) {

            $item = PostingItem::where('posting_id', $order_posting->posting_id)
                ->where('id', $order_posting->posting_item_id)
                ->first();

            $item->update([
                'quantity' => (int) $item->quantity - (int) $order_posting->quantity >= 0 ? (int) $item->quantity - (int) $order_posting->quantity : 0,
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
