<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;

class CustomerExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Customer::class);

        return \Excel::download(
            new CustomerExport,
            'Customer List - ' . request()->from.' - ' .request()->to. '.xlsx'
        );
    }
}
