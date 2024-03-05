<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Store;
use App\Models\AccessRequestEmailTemplate;
use App\Models\Auction;

class AuctionAccessRequestApproved extends Mailable
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
        $this->template = AccessRequestEmailTemplate::selectedFields()
                          ->where('access_request_email_templates.auction_id',$access_request->auction_id)
                          ->where('access_request_email_templates.type','=','Approved')
                          ->first()
                          ->toArray();

        $this->auction = Auction::selectedFields()
                                ->where('auctions.auction_id', $access_request->auction_id)
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
        return $this->view('emails.auction-access-requests.approved')
                    ->subject('Your Access has been Approved')
                    ->with('action', 'https://hmr.ph/auctions/'.$this->auction['slug'].'/details')
                    ->with('access_request', $this->access_request)
                    ->with('store', $this->store)
                    ->with('template', $this->template);
    }
}
