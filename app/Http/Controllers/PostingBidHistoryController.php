<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posting;
use App\Models\BidHistory;

class PostingBidHistoryController extends Controller
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

    public function index(Posting $posting)
    {
        $query = BidHistory::selectedFields()
                    ->where('posting_id', $posting->posting_id)
                    ->joinBidderNumber()
                    ->orderBy('bid_amount','DESC');
                    
        return $this->response($query);
    }

}
