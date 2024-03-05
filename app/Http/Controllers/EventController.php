<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventRequest;
use App\Processes\EventProcess;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
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
        $this->authorize('list', Event::class);

        $query = Event::selectedFields()
                    ->where('store_id', session()->get('store_id'))
                    ->withRelations()
                    ->searchable()
                    ->sortable();

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, EventProcess $process)
    {
        $this->authorize('create', Event::class);

        $process->create();

        activity()
            ->performedOn($process->event())
            ->withProperties($process->event())
            ->log('Event has been created');

        return [
            'success' => 1,
            'data' => $process->event()
        ];
    }

    public function update(EventRequest $request, EventProcess $process, Event $event)
    {
        $this->authorize('update', $event);

        if($event->published_date)
            abort(403, "Event is Already Published");

        $process->find($event->event_id)
            ->update();

        activity()
            ->performedOn( $process->event() )
            ->withProperties($process->event())
            ->log('Event has been updated');

        return [
            'success' => 1,
            'data' => $process->event()
        ];
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);

        return Event::where('event_id', $event->event_id)
            ->withRelations()
            ->first();
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        if($event->published_date)
            abort(403, "Event is Already Published");

        DB::transaction(function() use($event){
            $event->update([
                'deleted_by'    => auth()->id(),
            ]);

            $event->delete();

            activity()
                ->performedOn( $event )
                ->withProperties($event)
                ->log('Event has been deleted');
        });

        return [ 'success'   => 1 ];
    }
}
