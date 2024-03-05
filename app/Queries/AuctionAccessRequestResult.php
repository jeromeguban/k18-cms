<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\AuctionAccessRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuctionAccessRequestResult
{
	public static function query()
	{
		$query = AuctionAccessRequest::select([
				'auction_access_requests.status',
	            'auction_access_requests.evaluated_at',
	            'auctions.auction_number',
	            'customers.customer_firstname',
	            'customers.customer_lastname',
	            'customer_login_credentials.mobile_no',
	            'customer_login_credentials.email'		
			]);
            $query->searchable();
			$query->leftjoin('customers', 'customers.customer_id', '=', 'auction_access_requests.customer_id');
       	 	$query->leftjoin('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'auction_access_requests.customer_id');
        	$query->join('auctions', 'auctions.auction_id', '=', 'auction_access_requests.auction_id');
			$query->when(request()->auction_id, function($query){
				$query->where('auction_access_requests.auction_id', request()->auction_id);
			});


		return $query;
	}
}
