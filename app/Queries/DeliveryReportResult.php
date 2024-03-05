<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeliveryReportResult
{

    public static function query()
    {
        $from = Carbon::parse(request()->from.' 00:00:00')->toDateTimeString();
        $to = Carbon::parse(request()->to.' 00:00:00')->toDateTimeString();

        $query = Order::select([
            'orders.id',
            'orders.order_date',
            'customers.customer_firstname',
            'customers.customer_lastname',
            'orders.address',
            DB::raw('SUM(order_postings.price) as total_order_amount'),
            'order_postings.shipping_fee',
            'orders.courier_details'
        ]);
        $query->join('customers', 'customers.customer_id', 'orders.customer_id');
        $query->join('order_postings', 'order_postings.order_id', 'orders.id');
        $query->groupBy(['orders.id', 'order_postings.shipping_fee']);
        $query->whereBetween('orders.order_date', [$from, $to]);
        $query->whereNotNull('orders.courier_details');
        $query->whereNotNull('orders.payment_id');
        $query->where('order_status', '!=', 'Cancelled');
        $query->whereJsonDoesntContain('orders.courier_details', ['courier' => 'Book by HMR']);
        $query->where('orders.store_id', session()->get('store_id'));
        
        return $query;
    }
}
