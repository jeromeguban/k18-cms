<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use App\Queries\ProductCatalogResult;
use Maatwebsite\Excel\Concerns\FromView;

class ProductCatalogExport implements FromView
{

    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }


    private function getData()
    {

    	$data = [];

        $event = Event::find(request()->event_id);

    	$postings = ProductCatalogResult::query()->get();

        $data = [
            'postings'   => $postings,
            'event'	     => $event,
        ];

    	return $data;

    }


    private function getTemplate()
    {

    	return 'excel.product-catalog-results';

    }
}
