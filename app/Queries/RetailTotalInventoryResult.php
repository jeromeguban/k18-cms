<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\PostingItem;
use App\Models\PostingItemHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RetailTotalInventoryResult
{
	public static function query()
	{
		$from = Carbon::parse(request()->from.' 00:00:00')->toDateTimeString();
		$to = Carbon::parse(request()->to.' 23:59:59')->toDateTimeString();

		$earliestHistoryIdSubQuery = PostingItemHistory::selectRaw('MIN(id) as id, posting_item_id')
			->groupBy('posting_item_id');

		
		$query = PostingItem::select([
			'posting_items.updated_at',
			'posting_items.name',
			'stores.store_name',
			DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS listers_name"),
			'earliest_history.quantity_log AS first_quantity_log',
			DB::raw('earliest_history.quantity_log * posting_items.unit_price as first_total_amount')
		]);

		$query->join('stores', 'stores.id', '=', 'posting_items.store_id');
		$query->join('users', 'users.id', '=', 'posting_items.created_by');
		$query->joinSub($earliestHistoryIdSubQuery, 'earliest_id', 'earliest_id.posting_item_id', '=', 'posting_items.id');
		$query->join('posting_item_histories as earliest_history', 'earliest_history.id', '=', 'earliest_id.id');

		$query->whereBetween('posting_items.created_at', [$from, $to]);
		$query->where('stores.store_company_code', 'HRH');
		if(request()->store != 'all'){
			$query->where('posting_items.store_id', (int) request()->store);
		}

		$query->groupBy(['posting_items.updated_at', 'stores.store_name', 'users.first_name', 'users.last_name', 'posting_items.id', 'posting_items.name']);

		return $query;
	}
}
