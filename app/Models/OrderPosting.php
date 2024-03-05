<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encryptable;
class OrderPosting extends Model
{
    use Encryptable, SoftDeletes;

    protected $table = "order_postings";

	protected $hidden = [
        'created_at',
		'updated_at',
		'deleted_at',
		'cancelled_date',
		'cancelled_by'
    ];

	protected $encryptable = [
        'customer_firstname',
        'customer_lastname',
        'email',
		'customer_name'
    ];

	protected $searchable_fields = [
        'customers.customer_firstname_index',
        'customers.customer_lastname_index',
        'customer_login_credentials.email_index',
        'customer_login_credentials.mobile_no_index',
        'reference_code'
    ];

	public function scopeJoinOrder($query)
	{
		$query->addSelect([
					'orders.*',
				]);

		$query->join('orders', 'orders.id', '=', 'order_postings.order_id');
	}

	public function scopeJoinStore($query)
	{
		$query->addSelect([
            'stores.store_name',
			'stores.reference_id as store_reference_id',
		]);

		$query->join('stores', 'stores.id', '=', 'order_postings.store_id');

	}

	public function scopeJoinPosting($query)
	{
		$query->addSelect([
            'postings.*',
			'posting_items.*',
		]);

		$query->join('postings', 'postings.posting_id', '=', 'order_postings.posting_id');
        $query->leftjoin('posting_items', 'postings.posting_id', '=', 'posting_items.posting_id');

	}

	public function scopeJoinAuctionPosting($query)
	{
		$query->addSelect([
			'postings.bid_amount',
			'postings.processing_fee',
			'postings.name',
			'postings.banner'
		]);

		$query->join('postings', 'postings.posting_id', '=', 'order_postings.posting_id');

	}

	public function scopeJoinCustomer($query)
    {
        $query->addSelect([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customer_login_credentials.email'

        ]);

        $query->join('customers', 'order_postings.customer_id', '=', 'customers.customer_id');
        $query->join('customer_login_credentials', 'customers.customer_id', '=', 'customer_login_credentials.customer_id');


        return $query;
    }

	 //Relationships
    public function order()
    {
		return $this->belongsTo(Order::class, 'id');

    }
}

