<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Models\EventAccessRequest;
use App\Models\CustomerLoginCredential;
use App\Exports\EventAccessRequestExport;

class EventAccessRequestController extends Controller
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
        $this->authorize('list', EventAccessRequest::class);

        $query = EventAccessRequest::selectedFields()
                                ->joinCustomer()
                                ->joinCustomerLoginCredential()
                                ->joinEvent()
                                ->whereQueryScopes()
                                ->where('events.store_id', session()->get('store_id'))
                                ->withRelations()
                                ->sortable()
                                ->searchable()
                                ->orderBy('event_access_requests.id', 'DESC');

         $response = $this->response($query);

         (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($event_access_request) {
            $event_access_request =   (new ModelDecrypter)->decryptModel($event_access_request);
        });

       return $response; 
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $this->authorize('create', EventAccessRequest::class);

        $request->validate([
            'event_id' => 'required',
            'customer_id' => 'required',
           
        ], [],[
            'deleted_at'   => null,
            'deleted_by'   => null,
        ]);

        \DB::transaction(function() use($request){

            $event_access_request = EventAccessRequest::updateOrCreate([
                    'event_id'     => $request->event_id,
                    'customer_id'  => $request->customer_id
                ],[
                    'event_id'     => $request->event_id,
                    'customer_id'  => $request->customer_id
                ]);

            $event = Event::where('event_id', $request->event_id)->first();

            $customer_login_credential = CustomerLoginCredential::where('customer_id', $request->customer_id)->first();

            $customer_login_credential->update([
                'event_restrictions' => $event && $event->restriction ? json_encode([$event->event_id]) : null
            ]);
            
            activity()
                ->performedOn($event_access_request)
                ->withProperties($event_access_request)
                ->log('Customer has been added to Event');

            return [
                'success' => 1,
                'data' => $event_access_request
            ];

        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventAccessRequest $event_access_request)
    {
        
        $this->authorize('delete', $event_access_request);

        // return $event_access_request;
     
        $event_access_request->update([
            'deleted_by'   => auth()->id()
        ]);

        $event_access_request->delete();

        activity()
            ->performedOn( $event_access_request )
            ->withProperties($event_access_request)
            ->log('Auction Customer Request has been deleted');

        return [ 
            'success' => 1 
        ];

    }

    public function export()
    {
        if(!request()->event_id)
            abort(403, "Please Select an Auction");

        return \Excel::download(new EventAccessRequestExport,
            'Access Request List - '.now()->toDateTimeString().'.xlsx');
    }
}
