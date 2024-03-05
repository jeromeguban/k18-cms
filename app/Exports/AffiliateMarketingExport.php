<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use App\Queries\AffiliateMarketingResult;
use Illuminate\Contracts\View\View;

class AffiliateMarketingExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = AffiliateMarketingResult::query()->get();

        $data['affiliates'] = $query;

        $data = [
            'affiliates' => $query
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.affiliate-marketing';
    }
}
