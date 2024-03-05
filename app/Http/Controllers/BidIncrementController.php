<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BidIncrementController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', Auction::class);

        $auction = Auction::where('auction_id',request()->auction_id)->first();

        $bid_increment = Redis::get("auctions_".$auction->auction_id."_increment_table");

        $bid_increment = json_decode($bid_increment,true);

        return  $bid_increment;

    }


}
