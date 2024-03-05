<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\Posting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductCatalogResult
{
	public static function query()
    {
        $query = Posting::select([
            'postings.name',
            'postings.banner',
            'postings.description',
            'postings.unit_price',
            'postings.suggested_retail_price'

        ])
        ->where('postings.event_id', request()->event_id)
        ->groupBy(['postings.name', 'postings.description', 'postings.banner', 'postings.description', 'postings.unit_price', 'postings.suggested_retail_price'])
        ->whereNull('postings.deleted_at');


        return $query;
    }


}
