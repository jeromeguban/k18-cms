<?php

namespace App\Http\Controllers;

use App\Models\AccessRequestEmailTemplate;
use App\Models\AuctionAccessRequest;
use App\Processes\AuctionAccessRequestProcess;
use Illuminate\Http\Request;

class AuctionAccessRequestApproveController extends Controller
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
    public function store(Request $request, AuctionAccessRequest $access_request, AuctionAccessRequestProcess $process)
    {
        $this->authorize('create', AuctionAccessRequest::class);

        $email_template = AccessRequestEmailTemplate::selectedFields()
            ->where('access_request_email_templates.auction_id', $access_request->auction_id)
            ->where('access_request_email_templates.type', '=', 'Approved')
            ->first();

        if (!$email_template) {
            abort(422, "Oops! No Approved Email Template! Please Create First!");
        }

        $process->find($access_request->id)->approve();

        activity()
            ->performedOn($access_request->refresh())
            ->withProperties($access_request->refresh())
            ->log('Auction Access Request has been approved');

        return [
            'success' => 1,
            'data' => $access_request->refresh(),
        ];
    }
}
