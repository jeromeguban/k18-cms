<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CacheHelper;
use App\Models\Auction;
use App\Models\Posting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SimulcastRefreshAuctionCacheController extends Controller
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
        
        (new CacheHelper)->setModelCache($auction);

        $postings = Posting::wherePublished()
                        ->where('auction_id', $auction->auction_id)
                        ->orderByRaw("CHAR_LENGTH(lot_number) ASC, lot_number ASC")
                        ->get();

        $postings->each(function($posting){
            (new CacheHelper)->setModelCache($posting);
        });

        activity()
            ->performedOn( $auction )
            ->withProperties($auction)
            ->log('Auction Cache has been Refreshed');

        return [ 'success'   => 1 ];
    }
}
