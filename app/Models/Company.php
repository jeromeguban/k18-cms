<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Company extends Model
{
    use SoftDeletes;


    public function scopeJoinForPayables($query, $from, $to)
    {
        $for_payable_postings = OrderPosting::select([
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
                                    'stores.company_id'
                                ])
                                ->join('orders', 'orders.id', '=', 'order_postings.order_id')
                                ->join('payments', 'payments.id', '=', 'orders.payment_id')
                                ->join('stores', 'stores.id', '=', 'orders.store_id')
                                ->join('companies', 'companies.id', '=', 'stores.company_id')
                                ->when($from && $to, function($query) use ($from, $to) {
                                       $query->whereBetween('payments.created_at', [$from, $to]);
                                })
                                ->whereNotNull('orders.payment_id')
                                ->whereNull('order_postings.payable_id')
                                ->whereNull('orders.cancelled_date')
                                ->whereNull('orders.deleted_at')
                                ->whereNull('payments.deleted_at')
                                ->where('orders.payment_status', 'Paid')
                                ->groupBy([
                                    'stores.company_id',
                                ]);

        $for_payables = DB::table(DB::raw("({$for_payable_postings->toSql()}) AS for_payable_postings"))
                            ->mergeBindings($for_payable_postings->getQuery())
                            ->select([
                                DB::raw('SUM(for_payable_postings.sub_total_amount) AS sub_total_amount'),
                                DB::raw('SUM(for_payable_postings.discount_total_amount) AS discount_total_amount'),
                                DB::raw('SUM(for_payable_postings.total_sold_amount) AS total_sold_amount'),
                                DB::raw('SUM(for_payable_postings.total_commission) AS total_commission'),
                                DB::raw('SUM(for_payable_postings.payable_amount) AS payable_amount'),
                                'for_payable_postings.company_id'
                            ])
                            ->groupBy([
                                'for_payable_postings.company_id'
                            ]);

        $query->addSelect([
            'for_payables.sub_total_amount',
            'for_payables.discount_total_amount',
            'for_payables.total_sold_amount',
            'for_payables.total_commission',
            DB::raw('SUM(IFNULL(costs.amount,0)) AS total_costs'),
            DB::raw('(for_payables.payable_amount - SUM(IFNULL(costs.amount,0))) AS total_payable_amount'),
            'for_payables.company_id'
        ]);

        $query->joinSub($for_payables, 'for_payables', function($join){
            $join->on('for_payables.company_id', '=', 'companies.id');
        });

        $query->leftJoin('costs', function($join){
            $join->on('costs.company_id', '=', 'companies.id')
                ->whereNull('costs.payable_id');
        });

        $query->groupBy([
            'companies.id',
            'for_payables.total_sold_amount',
            'for_payables.sub_total_amount',
            'for_payables.discount_total_amount',
            'for_payables.total_commission',
            'for_payables.payable_amount',
        ]);
    }
}
