<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\BidderNumber;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BidderPerAuctionResult
{
	public static function query()
	{
		$query = BidderNumber::select([
			'auctions.auction_number',
			'bidder_numbers.bidder_number',
			'customers.customer_firstname',
    		'customers.customer_lastname',
    		'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
            'customers.created_at',
		]);
		$query->join('customers', 'customers.customer_id', '=', 'bidder_numbers.customer_id');
		$query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'customers.customer_id');
		$query->join('auctions', 'auctions.auction_id', '=', 'bidder_numbers.auction_id');
		$query->when(request()->auction_id, function($query){
			$query->where('auctions.auction_id', request()->auction_id);
			$query->orderByRaw('CHAR_LENGTH(bidder_numbers.bidder_number), bidder_numbers.bidder_number');
		});
	
		return $query;
	}
}
