<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Helpers\ModelDecrypter;
use App\Queries\RetailOrderResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RetailOrderExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = RetailOrderResult::query()->get();

        $data['retails'] = $query;

        $data['retails'] = (new ModelDecrypter)->decryptCollection($query);

        $data = [
            'retails' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.retail-order';
    }
}
