<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Store;
use App\Models\EventAccessRequestTemplate;
use App\Models\Event;

class EventAccessRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    protected $access_request,  $template, $event, $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($access_request)
    {
        $this->template = EventAccessRequestTemplate::selectedFields()
                          ->where('event_access_request_templates.event_id',$access_request->event_id)
                          ->where('event_access_request_templates.type','=','Approved')
                          ->first()
                          ->toArray();

        $this->event = Event::selectedFields()
                                ->where('events.event_id', $access_request->event_id)
                                ->first()
                                ->toArray();
                          
        $this->access_request = $access_request->toArray();
        $this->store = Store::find(session()->get('store_id'));

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event-access-requests.approved')
                    ->subject('Your Access has been Approved')
                    ->with('action', 'https://hmr.ph/events/'.$this->event['slug'].'/products')
                    ->with('access_request', $this->access_request)
                    ->with('store', $this->store)
                    ->with('template', $this->template);
    }
}
