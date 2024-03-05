<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Posting;
use Illuminate\Http\Request;
use App\Models\Searchable\Posting as SearchablePosting;

class EventHoldPostingController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {

        $this->authorize('hold-posting', $event);

        \DB::transaction(function() use ($event) {

            $posting_count = Posting::where('event_id', $event->event_id)->count();
            if(!$posting_count){
                abort(403, 'Sorry! You dont have any Products for this Event. ');
            }

            $holded_at = now()->toDateTimeString();

            $event->update([
                'holded_at' => now()->toDateTimeString()
            ]);

            Posting::where('event_id', $event->event_id)
                ->update([
                    'event_holded_at'   => $holded_at
                ]);

            SearchablePosting::where('event_id', $event->event_id)
                ->searchable();

            activity()
                ->performedOn($event)
                ->withProperties($event)
                ->log('Event Postings has been on hold');
        });


        return [
            'success' => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('hold-posting', $event);

        \DB::transaction(function() use ($event) {

            $event->update([
                'holded_at' => null
            ]);

            Posting::where('event_id', $event->event_id)
                ->update([
                    'event_holded_at'   => null
                ]);

            SearchablePosting::where('event_id', $event->event_id)
                ->searchable();

            activity()
                ->performedOn($event)
                ->withProperties($event)
                ->log('Event Postings has been removed from being on hold');
        });

        return [
            'success' => 1
        ];
    }
}
