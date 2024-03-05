<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Queries\CustomerResult;
use App\Helpers\ModelDecrypter;
use Maatwebsite\Excel\Concerns\FromView;


class CustomerExport implements FromView
{

    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }


    private function getData()
    {

        $data = [];

        $query = CustomerResult::query();

        $customers = (new ModelDecrypter)->decryptCollection($query);


        $data = [
            'customers' => $customers,
            'from' => request()->from,
            'to' => request()->to,
        ];

        return $data;
    }


    private function getTemplate()
    {

        return 'excel.customer-list';
    }
}
