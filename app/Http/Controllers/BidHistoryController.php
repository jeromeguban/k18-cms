<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Queries\BidHistoryResult;
use App\Helpers\ModelDecrypter;
use App\Exports\BidHistoryExport;

class BidHistoryController extends Controller
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

    public function generate()
    {
        $query = BidHistoryResult::query();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($bidder_history) {
            $bidder_history =   (new ModelDecrypter)->decryptModel($bidder_history);
        });


        return $response;
    }

    public function export()
    {
        return \Excel::download(new BidHistoryExport,
            'Bid History Report - '.now()->toDateTimeString().'.xlsx');
    }
}
