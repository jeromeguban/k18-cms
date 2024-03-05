<?php

namespace App\Http\Controllers;

use App\Models\PostingItem;
use App\Models\Posting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostingItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Posting $posting, PostingItem $posting_item )
    {
        $this->authorize('list', PostingItem::class);

        $query = PostingItem::where('posting_id', $posting->posting_id)
        ->searchable()
        ->sortable();

        return $this->response($query);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filterItem(PostingItem $posting_item)
    {
        $this->authorize('list', PostingItem::class);

        $query = PostingItem::select(['posting_items.id' ,'posting_items.posting_id','posting_items.sku', 'posting_items.quantity', 'posting_items.name', 'posting_items.unit_price', 'posting_items.store_id', 'posting_items.item_store_id'])
            ->where('posting_items.quantity', '>=' , 1)
            ->whereNotNull('posting_items.published_date')
            ->whereNull('posting_items.deleted_at')
            // ->where('posting_items.store_id', session()->get('store_id'))
            ->searchable()
            ->sortable();

        return $this->response($query);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PostingItem $posting_item)
    {
        $this->authorize('view', $posting_item);

        return PostingItem::where('id', $posting_item->id)
            ->withRelations()
            ->first();
    }

}
