<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Processes\EventRegistrationBannerProcess;

class EventRegistrationBannerController extends Controller
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
    public function index(Event $event)
    {
         // $this->authorize('view', $auction);

         $query = Event::selectedFields()
            ->where('event_id', $event->event_id)
            ->get();

        if(!$query){
            return 'Banners Not Found';
        }

        if($query) {
            return $query;
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'file'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        // dd($request->toArray());

        // return $request;
        $process = new EventRegistrationBannerProcess($event, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn( $event)
            ->withProperties( $event)
            ->log('Successfully added registration banner in Event');

            return [
                'success'   => 1,
                'data'      => $event->refresh()
            ];

    }
}
