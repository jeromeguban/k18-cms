<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\OrderPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PayableItemController extends Controller
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
    public function index(Payable $payable)
    {
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
            'postings.name AS posting_name',
        ])
            ->join('orders', 'orders.id', '=', 'order_postings.order_id')
            ->join('postings', 'postings.posting_id', '=', 'order_postings.posting_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->join('stores', 'stores.id', '=', 'orders.store_id')
            ->join('companies', 'companies.id', '=', 'stores.company_id')
            ->where('orders.payment_status', 'Paid')
            ->where('order_postings.payable_id', $payable->id)
            ->when(request()->store_id, function ($query) {
                $query->where('order_postings.store_id', request()->store_id);
            })
            ->groupBy([
                'companies.default_commission',
                'order_postings.id'
            ])
            ->sortable();


        return $this->response($query);
    }
}
