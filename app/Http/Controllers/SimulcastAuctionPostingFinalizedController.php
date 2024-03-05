<?php

namespace App\Http\Controllers;

use App\Models\BidHistory;
use Illuminate\Http\Request;
use App\Models\Searchable\Posting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Hammer\Item as HammerItem;
use App\Models\Hammer\Posting as HammerPosting;
use Illuminate\Support\Facades\Cache;

class SimulcastAuctionPostingFinalizedController extends Controller
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
    public function store(Request $request, Posting $posting)
    {   

        DB::transaction(function() use ($posting, $request) {

            Cache::lock("posting-{$posting->posting_id}-update")->get(function()  use ($posting, $request){
                $posting->update([
                    'bid_amount'     => null,
                    'finalized_date' => now()->toDateTimeString()
                ]);
            });

            //refresh elastic data
            Posting::where('posting_id', $posting->posting_id)->searchable();  
            
            //Update Hammer Posting
            Cache::lock("hammer-posting-{$posting->posting_id}-update")->get(function()  use ($posting, $request){
                HammerPosting::where('hmr_posting_id', $posting->posting_id)
                    ->update([
                        'bid_amount'     => null,
                        'finalized_date' => $posting->finalized_date,
                    ]);
            });
            
            Cache::lock("hammer-item-{$posting->posting_id}-update")->get(function()  use ($posting, $request){
                HammerItem::where('items.status', 'Outstanding')
                    ->when($posting->lot_id, function ($query) use($posting){
                        $query->where('items.lot_id', $posting->lot_id);
                    })
                    ->when($posting->item_id, function ($query) use($posting){
                        $query->where('items.item_id', $posting->item_id);
                    })->update([ 
                        'sold_amount'     =>  null,
                        'finalized_date' => now()->toDateTimeString(),
                    ]);
            });

            // Delete the first data of Bid History
            Cache::lock("posting-bid-history-{$posting->posting_id}-delete")->get(function()  use ($posting, $request){
                BidHistory::where('posting_id',$posting->posting_id)->delete();
            });
            //Store Redis Bid Histories
            $bid_histories =  json_decode( Redis::get("postings_".$posting->posting_id."_bid_histories"));

            if($bid_histories){
                foreach($bid_histories as $bid_history){
                    Cache::lock("posting-bid-history-{$bid_history->bid_amount}-create")->get(function()  use ($bid_history){
                        BidHistory::create([
                            'posting_id'       => $bid_history->posting_id,
                            'customer_id'      => $bid_history->customer_id,
                            'bid_amount'       => $bid_history->bid_amount,
                            'bidder_number_id' => $bid_history->bidder_number_id,
                            'bid_increment'    => $bid_history->bid_increment,
                            'max_bid'          => $bid_history->max_bid,
                            'server_time'      => $bid_history->server_time
                        ]);
                    });
                }
            }
        
            // Delete Seconr data of Bid History
            BidHistory::where('posting_id',$posting->posting_id)->delete();
    
            return [
                'success' => 1
            ];

            activity()
            ->performedOn($posting)
            ->withProperties($posting)
            ->log('Posting Has been Passed');

        });
        
    }

}
