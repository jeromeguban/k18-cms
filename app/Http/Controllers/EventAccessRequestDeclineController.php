<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventAccessRequest;
use App\Processes\EventAccessRequestProcess;

class EventAccessRequestDeclineController extends Controller
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
    public function store(Request $request, EventAccessRequest $access_request,EventAccessRequestProcess $process)
    {
        $this->authorize('decline', EventAccessRequest::class);

        $process->find($access_request->id)->decline();        
        
        activity()
            ->performedOn( $access_request->refresh() )
            ->withProperties($access_request->refresh())
            ->log('Event Access Request has been declined');

        return  [
            'success'   => 1,
            'data'      => $access_request->refresh()
        ];
    }
}
