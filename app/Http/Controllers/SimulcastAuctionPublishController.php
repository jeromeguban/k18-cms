<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Auction;
use App\Models\Posting;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Hammer\Auction as HammerAuction;
use App\Models\Searchable\Auction as SearchableAuction;
use App\Models\Searchable\Posting as SearchablePosting;

class SimulcastAuctionPublishController extends Controller
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

            $hammer_auction = HammerAuction::where('hmr_auction_id',  $auction->auction_id)->first();

            $auction->update([
                'published_date' => now()->toDateTimeString(),
                'published_by'   => auth()->id(),
                'ending_time'    => $hammer_auction->ending_time ?? Carbon::now()->addHours(5)
            ]);

            SearchableAuction::where('auction_id',$auction->auction_id)
                ->searchable();

            SearchablePosting::where('auction_id',$auction->auction_id)
                ->searchable();

            $postings = Posting::where('auction_id',$auction->auction_id)->get();

            $postings->each(function($posting){
                (new CacheHelper)->setModelCache($posting);
            });
            
        });

        activity()
            ->performedOn( $auction )
            ->withProperties($auction)
            ->log('Auction has been Published');


        return [ 'success'   => 1 ];

    }
}
