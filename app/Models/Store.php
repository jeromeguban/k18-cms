<?php

namespace App\Models;

use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ModelObjects\Courier as StoreCourierObject;
use DB;

class Store extends Model
{
    use SoftDeletes, Pagination;

    protected $searchable_fields = [
        'code',
        'store_type',
        'store_name',
        'store_company_name',
        'store_company_code',
    ];

    //Relationship
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function scopeJoinForPayablesPerStore($query, $from, $to, $excluded_items = [])
    {
        $for_payables = OrderPosting::select([
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
                                    'stores.id AS store_id'
                                ])
                                ->join('orders', 'orders.id', '=', 'order_postings.order_id')
                                ->join('payments', 'payments.id', '=', 'orders.payment_id')
                                ->join('stores', 'stores.id', '=', 'orders.store_id')
                                ->join('companies', 'companies.id', '=', 'stores.company_id')
                                ->when($from && $to, function($query) use ($from, $to) {
                                    $query->whereBetween('payments.created_at', [$from, $to]);
                                })
                                ->when($excluded_items && count($excluded_items), function($query) use ($excluded_items) {
                                    $query->whereNotIn('order_postings.id', $excluded_items);
                                })
                                ->whereNotNull('orders.payment_id')
                                ->whereNull('order_postings.payable_id')
                                ->whereNull('orders.cancelled_date')
                                ->whereNull('orders.deleted_at')
                                ->whereNull('payments.deleted_at')
                                ->where('orders.payment_status', 'Paid')
                                ->orderBy('stores.id')
                                ->groupBy([
                                    'stores.id',
                                ]);

        $query->addSelect([
            'for_payables.sub_total_amount',
            'for_payables.discount_total_amount',
            'for_payables.total_sold_amount',
            'for_payables.total_commission',
            DB::raw('SUM(IFNULL(costs.amount,0)) AS total_costs'),
            DB::raw('(for_payables.payable_amount - SUM(IFNULL(costs.amount,0))) AS total_payable_amount'),
            // 'for_payables.store_company_code'
        ]);

        $query->joinSub($for_payables, 'for_payables', function($join){
            $join->on('for_payables.store_id', '=', 'stores.id');
        });

        $query->leftJoin('costs', function($join){
            $join->on('costs.store_id', '=', 'stores.id')
                ->whereNull('costs.payable_id');
        });

        $query->groupBy([
            'stores.id',
            'for_payables.total_sold_amount',
            'for_payables.sub_total_amount',
            'for_payables.discount_total_amount',
            'for_payables.total_commission',
            'for_payables.payable_amount',
        ]);
    }

    // Join

    public function scopeLeftJoinCompany($query)
    {
        $query->addSelect([
            'companies.company_name as holding_company',
            'companies.company_code as store_company_code',
        ]);


        $query->leftJoin('companies', 'companies.id', '=', 'stores.company_id');

        return $query;
    }

    
    public function scopeLeftJoinClassifications($query)
    {
        $query->addSelect([
            'address.classifications.classification_name',
            'address.classifications.classification_type',
        ]);


        $query->leftjoin('address.classifications', 'stores.classification_id', '=', 'address.classifications.id');

        return $query;
    }



    // Scopes
    public function scopeWhereQueryScopes($query)
    {
    	if(request()->byUser)
    		$query->whereIn('id', auth()->user()->stores->pluck('id')->toArray());
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

     // Model Objects

     public function courier()
     {
         return new StoreCourierObject($this);
     }

     // Relations

     public function storeCouriers()
     {
         return $this->hasMany(StoreCourier::class);
     }
 
     public function couriers()
     {
         return $this->hasManyThrough(Courier::class, StoreCourier::class, 'store_id', 'id', 'id', 'courier_id')->whereNull('deleted_at');
     }
}
