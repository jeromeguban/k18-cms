<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Queries\RetailEventSummaryResult;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use Illuminate\Contracts\View\View;

class RetailEventSummaryExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = RetailEventSummaryResult::query()->get();


        $data['items'] = $query;

        $data['items'] = (new ModelDecrypter)->decryptCollection($query);

        $data = [
            'items' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.event-summary';
    }
}
