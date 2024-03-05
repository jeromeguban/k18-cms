<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Auction;
use App\Models\Posting;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostingAttributeData;

class AuctionCatalogPdfController extends Controller
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
    public function export(Request $request)
    {
        $data = [];

        $store = Store::where('id', session()->get('store_id'))->first();
        
        $postings = Posting::query()
            ->where('auction_id', request()->auction_id)
            ->get();

        $auction = Auction::findOrFail(request()->auction_id);
        
        $posting_attributes = PostingAttributeData::select([
                            'column_name',
                            'value',
                            'posting_id'
                        ])
                        ->whereIn('posting_id', $postings->pluck('posting_id'))
                        ->get();

        

        $column_names = $posting_attributes->whereIn('column_name', ['Color','Gear type','Plate Number','Mileage','Body Type','Fuel Type','Condition','Condition','Body condition','Battery', 'Last Registration', 'Field office', 'Interior', 'Exterior', 'Tires', 'Financing', 'Additional Notes:', 'Notes:'])->pluck('column_name')->unique()->flatten();

        $data = [
            'postings'          => $postings,
            'auction'           => $auction,
            'posting_attributes'=> $posting_attributes,
            'column_names'      => $column_names,
            'store'             => $store,
        ];

        $pdf = PDF::loadView('pdf.auction-catalog', $data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream("Bidder Lot Status" . Carbon::now() . ".pdf");
    }
}
