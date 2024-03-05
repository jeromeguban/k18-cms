<?php

namespace App\Http\Controllers;


use App\Models\Payable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\PayableExport;


class ExportPayableController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Payable::class);

        return \Excel::download( new PayableExport, 'Payable - ' . now()->toDateTimeString() . '.xlsx');

    }

}
