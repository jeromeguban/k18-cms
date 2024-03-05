<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Queries\BidderPerAuctionResult;
use App\Exports\BidderPerAuctionExport;

class BidderPerAuctionController extends Controller
{
    public function generate()
    {
        $query = BidderPerAuctionResult::query();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($bidder_per_auction) {
            $bidder_per_auction =   (new ModelDecrypter)->decryptModel($bidder_per_auction);
        });


        return $response;
    }

    public function export()
    {

        return \Excel::download(new BidderPerAuctionExport, 
            'Bidder Per Auction - '.now()->toDateTimeString().'.xlsx');
    }


}
