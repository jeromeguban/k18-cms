<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Queries\RetailEventSummaryResult;
use App\Exports\RetailEventSummaryExport;

class RetailEventSummaryResultController extends Controller
{
    public function index()
    {
        $query = RetailEventSummaryResult::query();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($event_summary) {
            $event_summary =   (new ModelDecrypter)->decryptModel($event_summary);
        });


        return $response;
    }

    public function export()
    {

        return \Excel::download(new RetailEventSummaryExport, 
            'Retail Event Summary - '.now()->toDateTimeString().'.xlsx');
    }

}
