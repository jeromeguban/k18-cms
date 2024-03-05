<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payable extends Model
{
    use SoftDeletes;


    public function scopeJoinCompany($query) 
    {
        $query->addSelect([
            'companies.company_name',
            'companies.company_code',
            'companies.default_commission',
        ]);

        $query->join('companies', 'companies.id', 'payables.company_id');
    }

    public function scopeLeftJoinAccountNumber($query)
    {
        $query->addSelect([
            'account_numbers.account_number',
            'account_numbers.account_name',
        ]);

        $query->leftJoin('account_numbers', 'account_numbers.id', '=', 'payables.account_number_id');
    }

    public function scopeLeftJoinBank($query)
    {
        $query->addSelect([
            'banks.name as bank_name',
        ]);

        $query->leftJoin('banks', 'payables.bank_id', '=', 'banks.id');
    }

    public function scopeJoinTotalPayables($query)
    {

        $total_payables = OrderPosting::select([
                            DB::raw('FORMAT(SUM((order_postings.price * order_postings.quantity)),2) AS sub_total_amount'),
                            DB::raw('FORMAT(SUM(order_postings.discount_price),2) AS discount_total_amount'),
                            DB::raw('FORMAT(SUM((order_postings.price * order_postings.quantity) - order_postings.discount_price),2) AS total_sold_amount'),
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
                            'order_postings.payable_id'
                        ])
                        ->join('orders', 'orders.id', '=', 'order_postings.order_id')
                        ->join('payments', 'payments.id', '=', 'orders.payment_id')
                        ->join('stores', 'stores.id', '=', 'orders.store_id')
                        ->join('companies', 'companies.id', '=', 'stores.company_id')
                        ->whereNotNull('orders.payment_id')
                        ->whereNotNull('order_postings.payable_id')
                        ->whereNull('orders.cancelled_date')
                        ->whereNull('orders.deleted_at')
                        ->whereNull('payments.deleted_at')
                        ->where('orders.payment_status', 'Paid')
                        ->groupBy([
                            'order_postings.payable_id',
                            'companies.default_commission'
                        ]);

        $query->addSelect([
            'total_payables.sub_total_amount',
            'total_payables.discount_total_amount',
            'total_payables.total_sold_amount',
            'total_payables.total_commission',
            DB::raw('SUM(IFNULL(costs.amount,0)) AS total_costs'),
            DB::raw('(total_payables.payable_amount - SUM(IFNULL(costs.amount,0))) AS total_payable_amount'),
        ]);

        $query->joinSub($total_payables, 'total_payables', function($join){
            $join->on('total_payables.payable_id', '=', 'payables.id');
        });

        $query->leftJoin('costs', function($join){
            $join->on('costs.payable_id', '=', 'payables.id')
                ->whereNotNull('costs.payable_id');
        });

        $query->groupBy([
            'payables.id',
            'total_payables.total_sold_amount',
            'total_payables.sub_total_amount',
            'total_payables.discount_total_amount',
            'total_payables.total_commission',
            'total_payables.payable_amount',
        ]);
    }

    public function scopeJoinPayablesPerStore($query)
    {
        $payable_stores = OrderPosting::select([
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
                                    'order_postings.payable_id',
                                    'stores.id AS store_id'
                                ])
                                ->join('orders', 'orders.id', '=', 'order_postings.order_id')
                                ->join('payments', 'payments.id', '=', 'orders.payment_id')
                                ->join('stores', 'stores.id', '=', 'orders.store_id')
                                ->join('companies', 'companies.id', '=', 'stores.company_id')
                                ->whereNotNull('orders.payment_id')
                                ->whereNotNull('order_postings.payable_id')
                                ->whereNull('orders.cancelled_date')
                                ->whereNull('orders.deleted_at')
                                ->whereNull('payments.deleted_at')
                                ->where('orders.payment_status', 'Paid')
                                ->orderBy('stores.id')
                                ->groupBy([
                                    'stores.id',
                                    'order_postings.payable_id'
                                ]);

        $query->addSelect([
            'payable_stores.sub_total_amount',
            'payable_stores.discount_total_amount',
            'payable_stores.total_sold_amount',
            'payable_stores.total_commission',
            DB::raw('SUM(IFNULL(costs.amount,0)) AS total_costs'),
            DB::raw('(payable_stores.payable_amount - SUM(IFNULL(costs.amount,0))) AS total_payable_amount'),
            'payable_stores.store_id',
            // 'payable_stores.store_company_code'
            'stores.store_name',
            'stores.code AS store_code',
        ]);

        $query->joinSub($payable_stores, 'payable_stores', function($join){
            $join->on('payable_stores.payable_id', '=', 'payables.id');
        });

        $query->join('stores', 'stores.id', '=', 'payable_stores.store_id');

        $query->leftJoin('costs', function($join){
            $join->on('costs.store_id', '=', 'stores.id')
                ->whereNull('costs.payable_id');
        });

     

        $query->groupBy([
            'payables.id',
            'payable_stores.store_id',
            'payable_stores.total_sold_amount',
            'payable_stores.sub_total_amount',
            'payable_stores.discount_total_amount',
            'payable_stores.total_commission',
            'payable_stores.payable_amount',
        ]);

        $query->orderBy('payable_stores.store_id');
    }

    public function scopeJoinPayableItems($query)
    {
        $query->addSelect([
            'postings.name as item_name',
            DB::raw('((order_postings.price * order_postings.quantity) - order_postings.discount_price) AS order_sold_amount'),
            DB::raw('(order_postings.price * order_postings.quantity) AS order_sub_total'),
            'order_postings.discount_price',
            'stores.store_name',
            'stores.code',
        ]);

        $query->Join('order_postings', 'order_postings.payable_id', '=', 'payables.id')
            ->Join('postings', 'postings.posting_id', '=', 'order_postings.posting_id')
            ->Join('stores', 'stores.id', '=', 'postings.store_id');

        $query->orderBy('stores.id');
    }

}
