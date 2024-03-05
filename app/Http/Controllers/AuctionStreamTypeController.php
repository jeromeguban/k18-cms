<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Elastic\Auction as SearchableAuction;
use App\Models\Auction;

class AuctionStreamTypeController extends Controller
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auction $auction) : array
    {
        $this->authorize('update', $auction);

        $request->validate([
            'stream_type'   => [
                'required',
                Rule::in(['Public', 'Private']),
            ]
        ]);

        $auction->update([
            'stream_type'   => $request->stream_type
        ]);

        // $auction->searchable();

        SearchableAuction::where('auction_id', $auction->auction_id)->searchable();

        activity()
            ->performedOn($auction)
            ->withProperties($auction)
            ->log('Auction Stream Type has been updated');

        return [
            'success'   => 1
        ];
    }
}
