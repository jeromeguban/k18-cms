<?php

namespace App\Http\Controllers\Reports;

use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\AuctionCatalogExport;

class AuctionCatalogController extends Controller
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
    public function export(Request $request, Auction $auction)
    {
        return \Excel::download(new AuctionCatalogExport, 
            'Auction Catalog  - '.now()->toDateTimeString().'.xlsx');
    }
}
