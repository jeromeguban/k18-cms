<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
use App\Models\PostingItem;

class RetailInventoryResult
{
    public static function query()
    {
        $query = PostingItem::select([
            'stores.store_company_code',
            'stores.store_name',
            'posting_items.name',
            'posting_items.sku',
            'posting_items.quantity',
            'posting_items.unit_price',
            'posting_items.suggested_retail_price',
            'posting_items.published_date',
        ]);
        $query->join('stores', 'stores.id', '=', 'posting_items.store_id');

        $query->when(request()->store != "all", function ($query) {
            $query->where('stores.id', request()->store);
        });

        $query->when(request()->filter_quantity == "With Zero Quantity", function ($query) {
            $query->where('posting_items.quantity', '>=', 0);
        });

        $query->when(request()->filter_quantity == "Without Zero Quantity", function ($query) {
            $query->where('posting_items.quantity', '>=', 1);
        });

        $query->when(request()->filter_quantity == "Published", function ($query) {
            $query->whereNotNull('posting_items.published_date');
        });

        $query->when(request()->filter_status == "Unpublished", function ($query) {
            $query->whereIsNull('posting_items.published_date');
        });

        $query->orderBy('stores.store_name', 'ASC');

        return $query;
    }
}
