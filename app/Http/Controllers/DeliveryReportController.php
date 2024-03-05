<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DeliveryExport;
use App\Queries\DeliveryReportResult;
use App\Helpers\ModelDecrypter;

class DeliveryReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    {

        $query = DeliveryReportResult::query();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($delivery_report) {
            $delivery_report =   (new ModelDecrypter)->decryptModel($delivery_report);
        });


        return $response;

    }

    public function export()
    {
        return \Excel::download(new DeliveryExport,
            'Delivery Report - '.now()->toDateTimeString().'.xlsx');
    }
}
