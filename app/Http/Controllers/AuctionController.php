<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('list', Auction::class);

        $query = Auction::selectedFields()
            ->withRelations()
            ->whereQueryScopes()
            ->searchable()
            ->sortable();

        // Select All Data if user has access on all Stores
        // $all_store = $this->authorize('all', Store::class) ? true : false;

        // if (!$all_store) {
        //     $query->where('auctions.store_id', session()->get('store_id'));
        // }

        $query->where('auctions.store_id', session()->get('store_id'));

        return $this->response($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Auction $auction)
    {

        $this->authorize('view', $auction);

        return Auction::selectedFields()
            ->where('auctions.auction_id', $auction->auction_id)
            ->first();

    }

}
