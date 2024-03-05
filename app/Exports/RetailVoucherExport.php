<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Helpers\ModelDecrypter;
use App\Queries\RetailVoucherResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RetailVoucherExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = RetailVoucherResult::query()->get();

        $data['retails'] = $query;

        $data['retails'] = (new ModelDecrypter)->decryptCollection($query);

        $data = [
            'retails' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.retail-voucher';
    }
}
