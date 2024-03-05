<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RetailEventSummaryResult
{
	public static function query()
	{
		$query = Event::select([
			'events.event_name',
			'events.starting_time',
			'postings.name as item_name',
			'postings.unit_price as price',
			'order_postings.quantity as ordered_quantity',
			DB::RAW('(order_postings.quantity * order_postings.price) as total '),
			'customers.customer_firstname',
			'customers.customer_lastname',
			'customer_login_credentials.email',
			'customer_login_credentials.mobile_no'
		]);
		$query->join('postings', 'postings.event_id', 'events.event_id');
		$query->join('order_postings', 'order_postings.posting_id', 'postings.posting_id');
		$query->join('customers', 'customers.customer_id', 'order_postings.customer_id');
		$query->join('customer_login_credentials', 'customer_login_credentials.customer_id', 'customers.customer_id');
		$query->when(request()->event_id, function($query){
			$query->where('events.event_id', request()->event_id);
		});
		$query->whereNull('order_postings.cancelled_date');
		$query->whereNull('order_postings.deleted_at');
	
		return $query;
	}
}
