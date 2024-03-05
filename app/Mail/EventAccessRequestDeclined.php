<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Store;
use App\Models\EventAccessRequestTemplate;

class EventAccessRequestDeclined extends Mailable
{
    use Queueable, SerializesModels;

    protected $access_request, $store, $template, $auction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($access_request)
    {
         $this->template = EventAccessRequestTemplate::selectedFields()
                          ->where('event_access_request_templates.event_id',$access_request->event_id)
                          ->where('event_access_request_templates.type','=','Declined')
                          ->first()
                          ->toArray();

                          
        $this->access_request = $access_request->toArray();

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event-access-requests.declined')
                    ->subject('Access Declined')
                    ->with('access_request', $this->access_request)
                    ->with('template', $this->template);
    }
}
