<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TotalInventoryExport;
use App\Queries\RetailTotalInventoryResult;

class TotalInventoryController extends Controller
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

        $query = RetailTotalInventoryResult::query();

        return $this->response($query);
    }

    public function export()
    {
        return \Excel::download(new TotalInventoryExport,
            'Retail Total Inventory Report - '.now()->toDateTimeString().'.xlsx');
    }
}
