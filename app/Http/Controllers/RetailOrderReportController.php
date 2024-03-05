<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Exports\RetailOrderExport;
use App\Queries\RetailOrderResult;

class RetailOrderReportController extends Controller
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

        $query = RetailOrderResult::query();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($query) {
            $query =   (new ModelDecrypter)->decryptModel($query);
        });

        return $response;
       
    }

    public function export()
    {

        return \Excel::download(
            new RetailOrderExport,
            'Retail Order Detailed Report - ' . now()->toDateTimeString() . '.xlsx'
        );
    }
}
