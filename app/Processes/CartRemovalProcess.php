<?php

namespace App\Processes;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

use Illuminate\Support\Facades\DB;

class CartRemovalProcess
{

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute handle process.
     *
     * @return void
     */

    public function handle()
    {
        DB::transaction(function () {
            $this->removeItems();
        });
    }

    private function removeItems()
    {

        // $auction_cart = Cart::selectedFields()
        //     ->join('order_postings', 'order_postings.posting_id', '=', 'carts.posting_id')
        //     ->whereNull('order_postings.cancelled_date')
        //     ->where('order_postings.category', 'Auction')
        //     ->get();

        // Cart::where('category', 'Auction')
        //     ->whereIn('carts.posting_id', $auction_cart->pluck('posting_id')->toArray())
        //     ->delete();

        // Cart::where('category', 'Retail')
        //     ->whereRaw('carts.expires_at + interval 15 day ', '<', now()->toDateTimeString())
        //     ->delete();
    }

}
