<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Exports\RetailVoucherExport;
use App\Queries\RetailVoucherResult;

class RetailVoucherReportController extends Controller
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

        $query = RetailVoucherResult::query();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($query) {
            $query =   (new ModelDecrypter)->decryptModel($query);
        });

        return $response;
       
    }

    public function export()
    {

        return \Excel::download(
            new RetailVoucherExport,
            'Voucher Report - ' . now()->toDateTimeString() . '.xlsx'
        );
    }
}
