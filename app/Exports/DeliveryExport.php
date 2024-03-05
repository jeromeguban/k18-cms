<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Helpers\ModelDecrypter;
use App\Queries\DeliveryReportResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeliveryExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];


        $query = DeliveryReportResult::query()->get();
        

        $data['items'] = $query;

        $data['items'] = (new ModelDecrypter)->decryptCollection($query);

        $data = [
            'items' => $query
        ];
        

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.delivery-report';
    }
}
