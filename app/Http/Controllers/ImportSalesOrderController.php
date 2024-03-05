<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Jobs\Retail\ImportSalesOrderJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ImportSalesOrderController extends Controller
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
     * Publish the specified resource from storage.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function index(Order $order)
    {
        $this->authorize('update', $order);

        ImportSalesOrderJob::dispatchSync($order, true);
        
        return [ 'success'   => 1 ];

    }

}