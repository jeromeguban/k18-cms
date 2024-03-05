<?php

namespace App\Http\Controllers\Api;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 900);

use App\Http\Controllers\Controller;
use App\Models\PostingItem;
use App\Models\Searchable\Category;
use Illuminate\Http\Request;

class KlaviyoCatalogController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {

        $categories = Category::elastic()->select([
                            'category_id',
                            'category_name'
                        ])
                        ->limit(10000)
                        ->get();

        return PostingItem::select([
                    'posting_items.id',
                    'posting_items.name AS title',
                    \DB::raw("REGEXP_REPLACE(posting_items.description, '<[^>]*>|&nbsp;', ' ') AS description"),
                    'posting_items.quantity AS inventory_quantity',
                    \DB::raw("IF(posting_items.unit_price > posting_items.suggested_retail_price, posting_items.suggested_retail_price, posting_items.unit_price) AS price"),
                    \DB::raw('CONCAT("'.env('HMR_PH_URL', 'https://hmr.ph').'/product/", postings.slug, "?from_gmc=1") AS link'),
                    \DB::raw('CONCAT("'.env('HMR_PH_URL', 'https://hmr.ph').'", postings.banner) AS image_link'),
                    \DB::raw('1 AS inventory_policy'),
                    'posting_items.posting_id',
                    'postings.categories'
                ])
                ->join('postings', 'postings.posting_id', '=', 'posting_items.posting_id')
                ->whereNotNull('posting_items.published_date')
                ->where('posting_items.quantity', '>', 0)
                ->searchable()
                ->sortable()
                ->orderBy('posting_items.published_date', 'DESC')
                ->get()
                ->each(function($posting_item) use ($categories) {

                    if($posting_item->categories && count(json_decode($posting_item->categories))){

                        
                        $category_names = $categories->whereIn('category_id', json_decode($posting_item->categories))->map(function($category){
                            return ucfirst(strtolower($category->category_name));
                        })->values()->toArray();

                        $posting_item->categories = $category_names;

                    } else {
                        $posting_item->categories = [];
                    }
                });

    }

}
