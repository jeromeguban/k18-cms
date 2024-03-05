<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\UserStore;
use Illuminate\Http\Request;

class EventPostingItemSearchController extends Controller
{
	public function index($barcode)
    {
        $this->authorize('list', Posting::class);

        $posting_items = PostingItem::where('posting_items.sku',$barcode)
            ->where('posting_items.store_id', session()->get('store_id'))
            ->get();

        return $posting_items;
    }
}
