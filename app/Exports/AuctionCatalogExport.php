<?php

namespace App\Exports;

use App\Models\Store;
use App\Models\Auction;
use App\Models\Posting;
use Illuminate\Contracts\View\View;
use App\Models\PostingAttributeData;
use Maatwebsite\Excel\Concerns\FromView;


class AuctionCatalogExport implements FromView
{

    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }


    private function getData()
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

    	return $data;

    }


    private function getTemplate()
    {
    	
    	return 'excel.auction-catalog';

    }
}