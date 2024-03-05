<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\PostingFilter;
use App\Models\PostingItem;
use App\Models\PostingTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
class PostingPublishController extends Controller
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
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function index(Posting $posting)
    {
        $this->authorize('publish', $posting);

        // if ($posting->category == 'Retail') {

        //     $total_published_items = PostingItem::where('posting_id', $posting->posting_id)
        //         ->whereNotNull('published_date')
        //         ->count();

        //     abort_if(!$total_published_items, 401, 'No published item(s) found.');
        // }

        DB::transaction(function () use ($posting) {

            $posting->update([
                'published_date'    => now()->toDateTimeString(),
                'published_by'      => auth()->id(),
            ]);

            SearchablePosting::where('posting_id', $posting->posting_id)->searchable();

            PostingTag::where('posting_id', $posting->posting_id)->update(['published_date' => now()->toDateTimeString()]);

            if ($posting->category == 'Auction'){
                Redis::set("postings_".$posting->posting_id, $posting->toJson());
            }
               
            if ($posting->category == 'Retail' || $posting->event_id){

                Redis::set("postings_".$posting->posting_id, $posting->toJson());

                $posting_items = PostingItem::where('posting_id', $posting->posting_id)->get();
    
                foreach($posting_items as $index => $posting_item) {

                    $posting_item->update([
                        'published_date'    => now()->toDateTimeString(),
                        'published_by'      => auth()->id(),
                    ]);

                    SearchablePostingItem::where('posting_id', $posting_item->id)->searchable();

                    Redis::set('postings_'.$posting->posting_id.'_items_'.$posting_item->id, $posting_item->toJson());
                }

            }
              

            // $posting_filters = PostingFilter::where('posting_id', $posting->posting_id)->get();

            // if ($posting_filters->count() > 0) {
            //     PostingFilter::where('posting_id', $posting->posting_id)
            //         ->update([
            //             'published_date'   => now()->toDateTimeString(),
            //             // 'published_by'     => auth()->id(),
            //         ]);
            // }

            activity()
                ->performedOn($posting)
                ->withProperties($posting)
                ->log('Posting has been Published');
        });

        return ['success'   => 1];
    }
}
