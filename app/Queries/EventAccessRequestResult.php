<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\EventAccessRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventAccessRequestResult
{
	public static function query()
	{
		$query = EventAccessRequest::select([
				'event_access_requests.status',
	            'event_access_requests.evaluated_at',
	            'events.event_name',
	            'customers.customer_firstname',
	            'customers.customer_lastname',
	            'customer_login_credentials.mobile_no',
	            'customer_login_credentials.email'		
			]);
            $query->searchable();
			$query->leftjoin('customers', 'customers.customer_id', '=', 'event_access_requests.customer_id');
       	 	$query->leftjoin('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'event_access_requests.customer_id');
        	$query->join('events', 'events.event_id', '=', 'event_access_requests.event_id');
			$query->when(request()->event_id, function($query){
				$query->where('event_access_requests.event_id', request()->event_id);
			});


		return $query;
	}
}
