<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\BidHistory;
use App\Models\Posting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PostingReportResult
{
	public static function query()
    {
        // Subquery to find the max server_time per auction and lot
        $subQuery1 = DB::table('bid_histories')
            ->select('posting_id', DB::raw('MAX(server_time) as max_time'))
            ->whereBetween('server_time', [
                Carbon::create(2023, 6, 2, 12, 0, 0),   // Start of the day
                Carbon::create(2023, 6, 8, 13, 59, 59)  // Up to 1:59 PM
            ])
            ->groupBy('posting_id');

        // Subquery to find the max bid_amount within the max server_time
        $subQuery2 = DB::table('bid_histories')
            ->joinSub($subQuery1, 'max_time_table', function ($join) {
                $join->on('bid_histories.posting_id', '=', 'max_time_table.posting_id')
                    ->on('bid_histories.server_time', '=', 'max_time_table.max_time');
            })
            ->select('bid_histories.posting_id', 'bid_histories.customer_id', 'max_time_table.max_time', DB::raw('MAX(bid_histories.bid_amount) as max_bid'))
            ->groupBy('bid_histories.posting_id', 'bid_histories.customer_id', 'max_time_table.max_time');

        $query = Posting::select([
            'postings.auction_number',
            'postings.lot_number',
            'max_bid_table.max_bid',
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
            'max_bid_table.max_time'
        ])
        ->joinSub($subQuery2, 'max_bid_table', function ($join) {
            $join->on('postings.posting_id', '=', 'max_bid_table.posting_id');
        })
        ->join('customers', 'customers.customer_id', '=', 'max_bid_table.customer_id')
        ->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'max_bid_table.customer_id')
        ->where('postings.auction_id', request()->auction_id)
        ->orderByRaw('CHAR_LENGTH(postings.lot_number), postings.lot_number');

        // Debugging: output the results of the query
        // dd($query->get()->toArray());

        return $query;
    }


}
