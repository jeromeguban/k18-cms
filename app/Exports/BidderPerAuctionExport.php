<?php

namespace App\Exports;

use App\Helpers\ModelDecrypter;
use App\Queries\BidderPerAuctionResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BidderPerAuctionExport implements FromView
{

    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    private function getData()
    {

        $data = [];

        $bidders = BidderPerAuctionResult::query()->get();

        $data['bidders'] = $bidders;

        $data['bidders'] = (new ModelDecrypter)->decryptCollection($bidders);

        return $data;

    }

    private function getTemplate()
    {

        return 'excel.bidder-per-auction';

    }
}
