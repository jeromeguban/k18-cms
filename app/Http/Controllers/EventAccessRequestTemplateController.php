<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventAccessRequestTemplate;

class EventAccessRequestTemplateController extends Controller
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
    public function index()
    {     
        $this->authorize('list', EventAccessRequestTemplate::class);

        $query = EventAccessRequestTemplate::selectedFields()
                    ->joinAuction()
                    ->searchable()
                    ->orderBy('event_access_request_templates.id', 'DESC');

        return $this->response($query);
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', EventAccessRequestTemplate::class);

        $request->validate([
            'event_id' => 'required',
            'title'      => 'required',
            'body'       => 'required',
            'type'       => 'required'
        ], [],[
            'event_id'      => 'Event',
            'title'         => 'Title',
            'body'          => 'Body',
            'type'          => 'Type'
        ]);

        $event_access_request_template = EventAccessRequestTemplate::create([
            'event_id'   => $request->event_id,
            'title'        => $request->title,
            'body'         => $request->body,
            'type'         => $request->type,
            'created_by'   => auth()->id(),
            'modified_by'  => auth()->id(),
        ]);

        activity()
            ->performedOn($event_access_request_template)
            ->withProperties($event_access_request_template)
            ->log('Event Template has been created');

        return [
            'success' => 1,
            'data' => $event_access_request_template
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, EventAccessRequestTemplate $event_access_request_template)
    {
        
        $this->authorize('update', $event_access_request_template);

        $request->validate([
            'event_id'   => 'required',
            'title'      => 'required',
            'body'       => 'required',
            'type'       => 'required'
        ], [], [
            'event_id'      => 'Event',
            'title'         => 'Title',
            'body'          => 'Body',
            'type'          => 'Type'
        ]);

        $event_access_request_template->update([
            'event_id'   => $request->event_id,
            'title'        => $request->title,
            'body'         => $request->body,
            'type'         => $request->type,
            'modified_by'  => auth()->id(),
        ]);

        activity()
            ->performedOn($event_access_request_template)
            ->withProperties($event_access_request_template)
            ->log('Event Template has been updated');

        return [
            'success' => 1,
            'data'    => $event_access_request_template
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventAccessRequestTemplate  $event_access_request_template
     * @return \Illuminate\Http\Response
     */
    public function show(EventAccessRequestTemplate $event_access_request_template)
    {
         $this->authorize('view', $event_access_request_template);

        return EventAccessRequestTemplate::selectedFields()
            ->where('id', $event_access_request_template->id)
            ->joinAuction()
            ->withRelations()
            ->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventAccessRequestTemplate $event_access_request_template)
    {
        
        $this->authorize('delete', $event_access_request_template);
     
        $event_access_request_template->update([
            'deleted_by'   => auth()->id()
        ]);

        $event_access_request_template->delete();

        activity()
            ->performedOn($event_access_request_template)
            ->withProperties($event_access_request_template)
            ->log('Event Template has been Delete');

        return [ 
            'success' => 1 
        ];

    }
}
