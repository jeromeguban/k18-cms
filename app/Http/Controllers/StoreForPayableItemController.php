<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Store;
use App\Models\OrderPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StoreForPayableItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware(['auth']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Store $store)
    {
        $from = Carbon::parse(request()->from . ' 00:00:00')->toDateTimeString();
        $to = request()->to ? Carbon::parse(request()->to . ' 23:59:59')->toDateTimeString() : now()->toDateTimeString();
 
        $query = OrderPosting::select([
                        DB::raw('SUM((order_postings.price * order_postings.quantity)) AS sub_total_amount'),
                        DB::raw('SUM(order_postings.discount_price) AS discount_total_amount'),
                        DB::raw('SUM((order_postings.price * order_postings.quantity) - order_postings.discount_price) AS total_sold_amount'),
                        DB::raw('(
                                companies.default_commission/100) * SUM(
                                (order_postings.price * order_postings.quantity) - order_postings.discount_price
                            ) AS total_commission'),
                        DB::raw('
                        (
                            SUM(
                                (order_postings.price * order_postings.quantity) - order_postings.discount_price
                            ) -
                            (
                                (companies.default_commission/100) * 
                                SUM(
                                    (order_postings.price * order_postings.quantity) - order_postings.discount_price
                                )
                            )
                        ) AS payable_amount'),
                        'order_postings.id AS order_posting_id',
                    ])
                    ->join('orders', 'orders.id', '=', 'order_postings.order_id')
                    ->join('payments', 'payments.id', '=', 'orders.payment_id')
                    ->join('stores', 'stores.id', '=', 'orders.store_id')
                    ->join('companies', 'companies.id', '=', 'stores.company_id')
                    ->when($store->store_company_code == 'HASI', function($query) {
                        $query->addSelect([
                            DB::raw('CONCAT("Auction ", postings.auction_number, " - Lot ", postings.lot_number) AS name'),
                            DB::raw('postings.description'),
                        ]);

                        $query->join('postings', 'postings.posting_id', '=', 'order_postings.posting_id');
          
                        $query->when(request()->auctions, function($query){
                            $query->whereIn('postings.auction_id', request()->auctions);
                        });
                    })
                    ->when($store->store_company_code == 'HRH', function($query) {

                        $query->addSelect([
                            DB::raw('CONCAT(posting_items.sku ," - ", posting_items.name) AS name'),
                            DB::raw('posting_items.description'),
                            DB::raw('posting_items.sku'),
                        ]);

                        $query->join('posting_items', 'posting_items.id', '=', 'order_postings.posting_item_id');

                        $query->when(request()->products, function($query){
                            $query->whereIn('posting_items.id', request()->products);
                        });                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               

                    })
                    ->whereNotNull('orders.payment_id')
                    ->whereNull('order_postings.payable_id')
                    ->whereNull('orders.cancelled_date')
                    ->whereNull('orders.deleted_at')
                    ->whereNull('payments.deleted_at')
                    ->where('orders.payment_status', 'Paid')
                    ->where('order_postings.store_id', $store->id)
                    ->whereBetween('payments.created_at', [$from, $to])
                    ->groupBy([
                        'companies.default_commission',
                        'order_postings.id'   
                    ])
                    ->limit(request()->limit)
                    ->sortable();

                   
        return $this->response($query);

    }

}
