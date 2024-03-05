<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\Auction as SearchableAuction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SimulcastAuctionUnpublishController extends Controller
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
     * Publish the specified resource from storage.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function index(Auction $auction)
    {
        DB::transaction(function() use ($auction) {

            $auction->update([
                'published_date'    => null,
                'published_by'     => null,
                'ending_time'     => now()->toDateTimeString(),
            ]);

            SearchableAuction::where('auction_id',$auction->auction_id)
                ->unsearchable();

            SearchablePosting::where('auction_id',$auction->auction_id)
                ->unsearchable();
            
        });

        activity()
            ->performedOn( $auction )
            ->withProperties($auction)
            ->log('Auction has been Unpublished');


        return [ 'success'   => 1 ];

    }
}
