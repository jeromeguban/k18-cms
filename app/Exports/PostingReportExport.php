<?php

namespace App\Exports;

use App\Helpers\ModelDecrypter;
use App\Queries\PostingReportResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PostingReportExport implements FromView
{

    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    private function getData()
    {

        $data = [];

        $items = PostingReportResult::query()->get();


        $data['items'] = $items;

        $data['items'] = (new ModelDecrypter)->decryptCollection($items);

        return $data;

    }

    private function getTemplate()
    {

        return 'excel.posting-report';

    }
}
