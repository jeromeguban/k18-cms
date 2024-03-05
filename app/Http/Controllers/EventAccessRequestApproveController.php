<?php

namespace App\Http\Controllers;

use App\Models\EventAccessRequest;
use App\Processes\EventAccessRequestProcess;
use Illuminate\Http\Request;

class EventAccessRequestApproveController extends Controller
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
    public function store(Request $request, EventAccessRequest $access_request, EventAccessRequestProcess $process)
    {
        $this->authorize('approve', EventAccessRequest::class);

        $process->find($access_request->id)->approve();

        activity()
            ->performedOn($access_request->refresh())
            ->withProperties($access_request->refresh())
            ->log('Event Access Request has been approved');

        return [
            'success' => 1,
            'data' => $access_request->refresh(),
        ];
    }
}
