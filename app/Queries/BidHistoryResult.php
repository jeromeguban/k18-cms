<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\BidHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BidHistoryResult
{
	public static function query()
	{
		$query = BidHistory::select([
				'postings.lot_number',
				'postings.description',
				'postings.starting_time',
				'postings.ending_time',
				DB::raw('IF(bid_histories.customer_id = 0, "Floor Bid", customers.customer_firstname) AS customer_firstname'),
    			DB::raw('IF(bid_histories.customer_id = 0, "", customers.customer_lastname) AS customer_lastname'),
				'bidder_numbers.bidder_number',
				'bid_histories.bid_amount',
				'bid_histories.created_at',
				DB::raw("(CASE
					WHEN bid_histories.max_bid = 1 THEN 'Max Bid'
					WHEN bid_histories.max_bid = 0 THEN 'Normal Bid'
					END) AS 'max_bid_indicator'"),
			]);
			$query->join('postings', 'postings.posting_id', '=', 'bid_histories.posting_id');
			$query->leftJoin('bidder_numbers', 'bid_histories.bidder_number_id', '=', 'bidder_numbers.bidder_number_id');
			$query->leftJoin('customers', 'bidder_numbers.customer_id', '=', 'customers.customer_id');
			$query->when(request()->auction_id , function($query){
				$query->where('postings.auction_id', request()->auction_id);
				$query->orderBy('bid_histories.bid_amount', 'DESC');
				$query->orderByRaw('CHAR_LENGTH(postings.lot_number), postings.lot_number');
			});

			$query->when(request()->auction_id && request()->posting_id , function($query){
				$query->where('postings.posting_id', request()->posting_id);
				$query->orderBy('bid_histories.bid_amount', 'DESC');
				$query->orderByRaw('CHAR_LENGTH(postings.lot_number), postings.lot_number');
			});


		return $query;
	}
}
