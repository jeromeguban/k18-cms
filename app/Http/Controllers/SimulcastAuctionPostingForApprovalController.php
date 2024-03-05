<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Searchable\Posting;

class SimulcastAuctionPostingForApprovalController extends Controller
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
        return [
            'success' => 1
        ];
    }


}
