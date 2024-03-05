<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Searchable\Posting;
use App\Processes\SimulcastMarkAsSoldProcess;
use App\Processes\SimulcastAuctionPostingMarkAsSoldProcess;

class SimulcastAuctionPostingMarkAsSoldController extends Controller
{
    protected $request, $posting, $customer = null, $bidder_number = null;
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
       (new SimulcastMarkAsSoldProcess($posting))->handle();

        return [
            'success' => 1
        ];
    }


   
}
