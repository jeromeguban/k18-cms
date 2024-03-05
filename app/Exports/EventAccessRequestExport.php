<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\EventAccessRequest;
use App\Queries\EventAccessRequestResult;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use Illuminate\Contracts\View\View;

class EventAccessRequestExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = EventAccessRequestResult::query()->get();

        // dd($query);

        $data['items'] = $query;

        $data['items'] = (new ModelDecrypter)->decryptCollection($query);

        $data = [
            'items' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.auction-access-request';
    }
}
