<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Auction;
use App\Models\Posting;

class AuctionPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Auction $auction)
    {
        $this->authorize('list', Posting::class);

        $query = Posting::selectedFields()
            ->searchable()
            ->where('postings.auction_id', $auction->auction_id)
            ->orderBy('postings.lot_number');

        return $this->response($query);
    }

    public function store(Auction $auction)
    {

        DB::transaction(function() use ($auction){

            Posting::where('postings.auction_id', $auction->auction_id)
            ->update([
                'for_approval' => 1,
            ]);;
            
            $auction->update([
                'for_approval' => 1,
            ]);
            
            
            activity()
                ->performedOn($auction)
                ->withProperties($auction)
                ->log('Auction has been set to for approval');
        });

        return [
            'success' => 1
        ];
    }

}
