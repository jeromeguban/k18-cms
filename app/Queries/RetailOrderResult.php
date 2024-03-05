<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);

use App\Models\Order;
use App\Models\OrderPosting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RetailOrderResult
{
    public static function query()
    {


        $order_postings = OrderPosting::select([
            DB::raw('SUM(order_postings.price) as total_order_price'),
            'order_postings.order_id'
        ])->groupBy(['order_postings.order_id']);

        $query = Order::select([
            'order_posting.total_order_price',
            'orders.reference_code',
            'orders.id as order_number',
            'stores.store_name',
            'orders.customer_name',
            'orders.contact_number',
            'orders.email',
            'orders.cancelled_date',
            'orders.created_at',
            'orders.order_status',
        ]);

        $query->JoinSub($order_postings, 'order_posting', function ($join) {
            $join->on('order_posting.order_id', '=', 'orders.id');
        });
        
        $query->join('stores', 'stores.id', '=', 'orders.store_id');

        $query->when(request()->store != "all" && request()->store, function ($query) {
            $query->where('orders.store_id', request()->store);
        });

        $query->when(request()->from && request()->to, function ($query) {
            $query->whereBetween('orders.created_at', [Carbon::parse(request()->from . ' 00:00:00')->toDateTimeString(), Carbon::parse(request()->to . ' 23:59:59')->toDateTimeString()]);
        });

        $query->when(request()->status == 'paid', function ($query) {
            $query->where('orders.order_status', 'Paid');
        });

        $query->when(request()->status == 'pending', function ($query) {
            $query->where('orders.order_status', 'Pending');
        });

        $query->when(request()->status == 'cancelled', function ($query) {
            $query->where('orders.order_status', 'Cancelled');
        });

        $query->when(request()->status == 'processing', function ($query) {
            $query->where('orders.order_status', 'Processing');
        });

        $query->when(request()->status == 'for-delivery', function ($query) {
            $query->where('orders.order_status', 'For Delivery');
        });

        $query->when(request()->status == 'completed', function ($query) {
            $query->where('orders.order_status', 'Completed');
        });

        $query->when(request()->status == 'failed-delivery', function ($query) {
            $query->where('orders.order_status', 'Failed Delivery');
        });

        $query->where('stores.store_company_code', 'HRH');
        $query->orderBy('stores.store_name', 'ASC');

        return $query;
    }
}
