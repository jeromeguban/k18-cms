<?php

namespace App\Http\Controllers;

use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PostingItemPublishController extends Controller
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
     * Publish the specified resource from storage.
     *
     * @param  \App\Models\PostingItem  $postingitem
     * @return \Illuminate\Http\Response
     */
    public function index(PostingItem $posting_item)
    {
        $this->authorize('publish', $posting_item);

        DB::transaction(function() use ($posting_item) {

            $posting_item->update([
                'published_date'    => now()->toDateTimeString(),
                'published_by'      => auth()->id(),
            ]);

            SearchablePostingItem::where('posting_id', $posting_item->id)->searchable();

            $total_published_item_count = PostingItem::where('posting_id',  $posting_item->posting_id)
                                ->whereNotNull('published_date')
                                ->count();

            $total_item_count = PostingItem::where('posting_id',  $posting_item->posting_id)
                                ->count();

            if($total_published_item_count == $total_item_count){

                $posting = Posting::where('posting_id',  $posting_item->posting_id)->first();
                
                SearchablePosting::where('posting_id', $posting->posting_id)->searchable();

                $posting->update([
                    'published_date'    => now()->toDateTimeString(),
                    'published_by'      => auth()->id(),
                ]);

                if ($posting->event_id)
                    Redis::set("postings_".$posting->posting_id, $posting->toJson());
                    Redis::set('postings_'.$posting->posting_id.'_items_'.$posting_item->id, $posting_item->toJson());
            }

            activity()
                ->performedOn($posting_item)
                ->withProperties($posting_item)
                ->log('Posting Item has been Published');
        });

        return [ 
            'success'   => 1 
        ];

    }
}
