<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class EventUnpublishController extends Controller
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
    public function index(Event $event)
    {
        $this->authorize('publish', $event);

        $postings = Posting::where('postings.event_id', $event->event_id)
                        ->whereNull('postings.deleted_at')
                        ->get();

        DB::transaction(function() use ($event, $postings) {

            $event->update([
                'published_date'    => null,
                'published_by'     => null,
            ]);
            
            // $postings->each(function($posting){
                // $posting->update([
                //     'published_date'    => null,
                //     'published_by'      => null,
                // ]);

                // $postingItems = PostingItem::where('posting_items.posting_id', $posting->posting_id)
                //                             ->get();



                // foreach($postingItems as $postingItem){
                //     $postingItem->update([
                //         'published_date'    => null,
                //         'published_by'      => null,
                //     ]);

                //      SearchablePostingItem::where('id', $postingItem->id)->unsearchable();
                // }

                // Redis::del("postings_".$posting->posting_id);
            });



            activity()
                ->performedOn( $event )
                ->withProperties($event)
                ->log('Event has been Unpublished');
        // });

        return [ 'success'   => 1 ];

    }
}
