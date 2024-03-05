<?php 

namespace App\Processes;

use App\Models\Event;
use App\Models\Store;
use App\Helpers\Gmail;
use App\Helpers\ModelDecrypter;
use App\Models\EventAccessRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventAccessRequestApproved;
use App\Mail\EventAccessRequestDeclined;
use App\Models\EventAccessRequestTemplate;

class EventAccessRequestProcess
{
	protected $request, $access_request, $template, $store, $event, $email;

	/**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{
		$this->request = $request ? (object) $request : request();
	}

	/**
     * Execute find process.
     *
     * @return void
    */

    public function find($id)
    {

    	$query = EventAccessRequest::selectedFields()
                    ->joinCustomer()
                    ->joinCustomerLoginCredential()
                    ->findOrFail($id);

        $this->access_request = (new ModelDecrypter)->decryptModel($query);


    	return $this;

    }

    /**
     * Retrive Access Request.
     *
     * @return 
    */

    public function accessRequest()
    {
    	return $this->access_request;
    }


    /**
     * Execute create process.
     *
     * @return void
    */

    public function approve()
    {
    	DB::transaction(function(){
    		$this->approveAccessRequest()
    			->sendApprovedNotification();
    	});
    }

    /**
     * Execute update process.
     *
     * @return void
    */

    public function decline()
    {
    	DB::transaction(function(){
             $this->sendDeclinedNotification()
                  ->declineAccessRequest();
        });
    }

    private function approveAccessRequest()
    {
    	 EventAccessRequest::where('id',$this->access_request->id)->update([
            'status'         => "Approved",
            'evaluated_by'   => auth()->id(),
            'evaluated_at'   => now()->toDateTimeString(),
        ]);

    	return $this;
    }

    private function declineAccessRequest()
    {
         EventAccessRequest::where('id',$this->access_request->id)->update([
            'status'         => "Declined",
            'evaluated_by'   => auth()->id(),
            'evaluated_at'   => now()->toDateTimeString(),
        ]);


        return $this;
    }

    private function sendApprovedNotification()
    {
    	
        // Mail::to($this->access_request->email)
        //     ->queue(new EventAccessRequestApproved($this->access_request));

         $this->template = EventAccessRequestTemplate::selectedFields()
                          ->where('event_access_request_templates.event_id',$this->access_request->event_id)
                          ->where('event_access_request_templates.type','=','Approved')
                          ->first()
                          ->toArray();

        $this->event = Event::selectedFields()
                                ->where('events.event_id', $this->access_request->event_id)
                                ->first()
                                ->toArray();

        $this->email = $this->access_request->email;
        
        $this->store = Store::find(session()->get('store_id'));


         (new Gmail())->to(strtolower($this->email))
                        ->view('emails.event-access-requests.approved')
                        ->with([
                            'subject'       => 'Your Access has been Approved',
                            'action'        => 'https://hmr.ph/events/'.$this->event['slug'].'/products',
                            'access_request'=>  $this->access_request->toArray(),
                            'store'         =>  $this->store,
                            'event'         =>  $this->event,
                            'template'      =>  $this->template,
                        ])
                        ->send();

        return $this;
    }

    private function sendDeclinedNotification()
    {
        // Mail::to($this->access_request->email)
        //     ->queue(new EventAccessRequestDeclined($this->access_request));

        $this->template = EventAccessRequestTemplate::selectedFields()
                          ->where('event_access_request_templates.event_id', $this->access_request->event_id)
                          ->where('event_access_request_templates.type','=','Declined')
                          ->first()
                          ->toArray();

        $this->event = Event::selectedFields()
                        ->where('events.event_id', $this->access_request->event_id)
                        ->first()
                        ->toArray();

        $this->email = $this->access_request->email;

         (new Gmail())->to(strtolower($this->email))
                        ->view('emails.event-access-requests.declined')
                        ->with([
                            'subject'       => 'Access Declined',
                            'access_request'=>  $this->access_request->toArray(),
                            'event'         =>  $this->event,
                            'template'      =>  $this->template,
                        ])
                        ->send();

        return $this;
    }
}