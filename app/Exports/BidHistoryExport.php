<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\BidHistory;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use App\Queries\BidHistoryResult;
use Illuminate\Contracts\View\View;

class BidHistoryExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = BidHistoryResult::query()->get();

        $data['items'] = $query;

        $data['items'] = (new ModelDecrypter)->decryptCollection($query);

        $data = [
            'items' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.bid-history';
    }
}
