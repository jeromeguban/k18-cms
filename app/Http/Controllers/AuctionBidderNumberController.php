<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\BidderNumber;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;


class AuctionBidderNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Auction $auction)
    {
        $this->authorize('list', Auction::class);
        
        $query = BidderNumber::selectedFields()
                    ->joinCustomer()
                    ->joinCustomerLoginCredential()
                    ->where('auction_id', $auction->auction_id)
                    ->orderBy('bidder_numbers.bidder_number')
                    ->get();

        return (new ModelDecrypter)->decryptCollection($query)->toJson();
    }
}
