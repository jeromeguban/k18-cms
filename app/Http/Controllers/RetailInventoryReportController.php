<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RetailInventoryExport;
use App\Queries\RetailInventoryResult;

class RetailInventoryReportController extends Controller
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

        $query = RetailInventoryResult::query();

        return $this->response($query);

    }

    public function export()
    {
       
        return \Excel::download(new RetailInventoryExport,
            'Retail Inventory : Detailed Report - '.now()->toDateTimeString().'.xlsx');
    }
}