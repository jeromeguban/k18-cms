<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Helpers\ModelDecrypter;
use App\Queries\RetailTotalInventoryResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TotalInventoryExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];


        $query = RetailTotalInventoryResult::query()->get();

        // dd($query->toArray());


        $data['retails'] = $query;

        $data = [
            'retails' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.total-inventory';
    }
}
