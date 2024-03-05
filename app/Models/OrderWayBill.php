<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encryptable;

class OrderWayBill extends Model
{
    use SoftDeletes, Encryptable;

    protected $table = 'order_waybills';

    protected $encryptable = [
        'email',
        'customer_name',
        'contact_number',
    ];

    protected $searchable_fields = [
        'orders.reference_code',
        'orders.id',
    ];


    public function scopeJoinOrder($query)
    {
        $query->addSelect([
            'orders.customer_name',
            'orders.contact_number',
            'orders.waybill_status',
            'orders.email',
            'orders.order_date',
            'orders.order_status',
            'orders.courier_details',
            'orders.address',
            \DB::raw('SUM(order_postings.price * order_postings.quantity) AS sub_total_amount'),
            \DB::raw('SUM(order_postings.discount_price) AS total_discount_price'),
            \DB::raw('SUM((order_postings.price * order_postings.quantity) - order_postings.discount_price) AS total_order_amount'),
            'order_postings.category',
        ]);

        $query->join('orders', 'orders.id', '=', 'order_waybills.order_id');
        $query->join('order_postings', 'order_postings.order_id', '=', 'orders.id');

        $query->groupBy([
            'order_waybills.id',
            'order_postings.category',
            'order_postings.order_id',
		]);

        return $query;
    }

    public function scopeJoinStore($query)
	{
		$query->addSelect([
            'stores.store_name',
		]);

		$query->join('stores', 'stores.id', '=', 'orders.store_id');

	}

    // public function scopeJoinWaybillStatus($query)
    // {
    //     $query->addSelect([
    //         'waybill_statuses.status'
    //     ]);

    //     return $query;
    // }
}
