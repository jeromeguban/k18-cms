<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use App\Models\Searchable\Posting as SearchablePosting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class EventPublishController extends Controller
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



        DB::transaction(function() use ($event) {

            $event->update([
                'published_date'    => now()->toDateTimeString(),
                'published_by'     => auth()->id(),
            ]);

            Redis::set("events_".$event->event_id, $event->toJson());

            $posting_ids = Posting::where('event_id', $event->event_id)
                            ->get()
                            ->pluck('posting_id')
                            ->values()
                            ->toArray();

            Posting::where('event_id', $event->event_id)
                ->update([
                    'term_id'           => $event->term_id ?? null,
                    'type'              => $event->type ?? null,
                    'starting_time'     => $event->starting_time ?? null,
                    'published_date'    => now()->toDateTimeString(),
                    'published_by'      => auth()->id(),
                ]);


            PostingItem::whereIn('posting_id', $posting_ids)
                ->update([
                    'published_date'    => now()->toDateTimeString(),
                    'published_by'      => auth()->id(),
                ]);

            SearchablePosting::where('event_id', $event->event_id)
                ->searchable();

            SearchablePostingItem::whereIn('posting_id', $posting_ids)
                    ->searchable();



            activity()
                ->performedOn( $event )
                ->withProperties($event)
                ->log('Event has been Published');
        });

        return [ 'success'   => 1 ];

    }
}
