<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Posting;
use App\Models\BidderNumber;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use Illuminate\Support\Facades\Redis;
use App\Models\Searchable\Posting as SearchablePosting;


class SimulcastAuctionPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Auction $auction)
    {
        $this->authorize('list', Posting::class);

        $query = SearchablePosting::elastic()
                    ->where('auction_id', $auction->auction_id)
                    ->orderBy('sequence')
                    ->limit(1000);

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($posting) {

            $bid_amount = Redis::get('postings_'.$posting->posting_id.'_bid_amount');

            if(($posting->bid_amount && $bid_amount > $posting->bid_amount) || !$posting->bid_amount && $bid_amount)
                $posting->bid_amount = $bid_amount;

            if($posting->attribute_data)                 
                $posting->attribute_data = json_decode($posting->attribute_data);
            
            $posting->images = is_array($posting->images) ? $posting->images : json_decode($posting->images);

        });

        return $response;
    }

    public function history(Posting $posting)
    {
        $this->authorize('list', Posting::class);

        $query = SearchablePosting::elastic()
                    ->where('sequence','<', $posting->sequence)
                    ->where('auction_id', $posting->auction_id)
                    ->orderBy('sequence', 'DESC')
                    ->limit(1);

        $response = $this->response($query);
        
        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($posting) {

            $bid_amount = Redis::get('postings_'.$posting->posting_id.'_bid_amount');

            if(($posting->bid_amount && $bid_amount > $posting->bid_amount) || !$posting->bid_amount && $bid_amount)
                $posting->bid_amount = $bid_amount;

            if($posting->attribute_data)                 
                $posting->attribute_data = json_decode($posting->attribute_data);
            
            $posting->images = is_array($posting->images) ? $posting->images : json_decode($posting->images);

            $bidder = BidderNumber::where('bidder_number_id',$posting->bidder_number_id)->first();
            
            $posting->bidder_number = $bidder ? $bidder->bidder_number : null;
            
        });
    
        return $response;
    }

    public function nextLot(Posting $posting)
    {
        $this->authorize('list', Posting::class);

        $query = SearchablePosting::elastic()
                    ->where('sequence','>', $posting->sequence)
                    ->where('auction_id', $posting->auction_id)
                    ->orderBy('sequence')
                    ->limit(1);

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($posting) {

            $bid_amount = Redis::get('postings_'.$posting->posting_id.'_bid_amount');

            if(($posting->bid_amount && $bid_amount > $posting->bid_amount) || !$posting->bid_amount && $bid_amount)
                $posting->bid_amount = $bid_amount;

            if($posting->attribute_data)                 
                $posting->attribute_data = json_decode($posting->attribute_data);
            
            $posting->images = is_array($posting->images) ? $posting->images : json_decode($posting->images);

        });

        return $response;
    }



    public function bidderNumbers(Auction $auction)
    {
        $this->authorize('list', Posting::class);

        $query = BidderNumber::selectedFields()
                    ->joinCustomer()
                    ->joinCustomerLoginCredential()
                    ->where('auction_id', $auction->auction_id)
                    ->get();

        return (new ModelDecrypter)->decryptCollection($query)->toJson();
    }
}
