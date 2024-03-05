<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use App\Queries\RetailInventoryResult;
use Illuminate\Contracts\View\View;

class RetailInventoryExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = RetailInventoryResult::query()->get();

        $data['retails'] = $query;

        $data = [
            'retails' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.retail-inventory';
    }
}
