<?php

namespace App\Processes;

use App\Helpers\ModelDecrypter;
use App\Models\BidderNumber;
use App\Models\Auction;
use App\Models\Store;
use App\Models\AccessRequestEmailTemplate;
use App\Models\AuctionAccessRequest;
use App\Mail\AuctionAccessRequestApproved;
use App\Mail\AuctionAccessRequestDeclined;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Helpers\Gmail;

class AuctionAccessRequestProcess
{
	protected $request, $access_request, $bidder_number, $auction, $template, $store;

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

    	$query = AuctionAccessRequest::selectedFields()
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
     * Retrive Bidder Number.
     *
     * @return
    */

    public function bidderNumber()
    {
    	return $this->bidder_number;
    }

    /**
     * Execute create process.
     *
     * @return void
    */

    public function approve()
    {
    	DB::transaction(function(){
    		$this->generateBidderNumber()
                ->approveAccessRequest()
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

    private function generateBidderNumber()
    {
    	if(!$this->access_request)
    		return;

        $auction = Auction::where('auction_id', $this->access_request->id)->first();

    	$this->bidder_number = BidderNumber::create([
            'customer_id'   => $this->access_request->customer_id,
            'auction_id'    => $this->access_request->auction_id,
            'bidder_number' => $auction->category = 'Simulcast' && BidderNumber::getLastestBidderNumber($this->access_request->auction_id) <= 1000
                                ? BidderNumber::getLastestBidderNumber($auction->auction_id) + 1000
                                : BidderNumber::getLastestBidderNumber($auction->auction_id),
            'agree_date'    => null,
            'is_approved'   => 1,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id()
        ]);

        Redis::set("bidder_number_".$this->bidder_number->bidder_number_id, $this->bidder_number->bidder_number);

        Redis::set("auction_{$this->bidder_number->auction_id}_customer_{$this->bidder_number->customer_id}", $this->bidder_number->bidder_number_id);

        activity()
            ->performedOn( $this->bidder_number )
            ->log('Bidder Number has been created using Auction Access Request');

    	return $this;
    }

    private function approveAccessRequest()
    {
    	 AuctionAccessRequest::where('id',$this->access_request->id)->update([
            'status'         => "Approved",
            'evaluated_by'   => auth()->id(),
            'evaluated_at'   => now()->toDateTimeString(),
        ]);

    	return $this;
    }

    private function declineAccessRequest()
    {
         AuctionAccessRequest::where('id',$this->access_request->id)->update([
            'status'         => "Declined",
            'evaluated_by'   => auth()->id(),
            'evaluated_at'   => now()->toDateTimeString(),
        ]);


        $this->bidder_number = BidderNumber::where('auction_id', $this->access_request->auction_id)
                                ->where('customer_id', $this->access_request->customer_id)
                                ->first();

        if($this->bidder_number){

            Redis::del("bidder_number_".$this->bidder_number->bidder_number_id, $this->bidder_number->bidder_number);

            Redis::del("auction_".$this->bidder_number->auction_id."_customer_".$this->bidder_number->customer_id, $this->bidder_number->bidder_number_id);

            $this->bidder_number->delete();

        }


        return $this;
    }

    private function sendApprovedNotification()
    {

        // Mail::to($this->access_request->email)
        //     ->queue(new AuctionAccessRequestApproved($this->access_request));

        $this->template = AccessRequestEmailTemplate::selectedFields()
                          ->where('access_request_email_templates.auction_id',$this->access_request->auction_id)
                          ->where('access_request_email_templates.type','=','Approved')
                          ->first()
                          ->toArray();

        $this->auction = Auction::selectedFields()
                                ->where('auctions.auction_id', $this->access_request->auction_id)
                                ->first()
                                ->toArray();

        $this->store = Store::find(session()->get('store_id'));

        (new Gmail())->to(strtolower($this->access_request->email))
                        ->view('emails.auction-access-requests.approved')
                        ->with([
                            'subject'       => 'Your Access has been Approved',
                            'action'        => 'https://hmr.ph/auctions/'.$this->auction['slug'].'/details',
                            'access_request'=>  $this->access_request->toArray(),
                            'template'      => $this->template,
                            'store'         => $this->store,
                        ])
                        ->send();

        return $this;
    }

    private function sendDeclinedNotification()
    {
        // Mail::to($this->access_request->email)
        //     ->queue(new AuctionAccessRequestDeclined($this->access_request));

        $this->template = AccessRequestEmailTemplate::selectedFields()
                          ->where('access_request_email_templates.auction_id',$this->access_request->auction_id)
                          ->where('access_request_email_templates.type','=','Declined')->first()->toArray();


        $this->auction = Auction::selectedFields()
                          ->where('auctions.auction_id', $this->access_request->auction_id)
                          ->first()
                          ->toArray();

        $this->store = Store::find(session()->get('store_id'));

        (new Gmail())->to(strtolower($this->access_request->email))
                        ->view('emails.auction-access-requests.declined')
                        ->with([
                            'subject'       => 'Your Access has been Declined',
                            'action'        => 'https://hmr.ph/auctions/'.$this->auction['slug'].'/details',
                            'access_request'=>  $this->access_request->toArray(),
                            'template'      => $this->template,
                            'store'         => $this->store,
                        ])
                        ->send();

        return $this;
    }
}
