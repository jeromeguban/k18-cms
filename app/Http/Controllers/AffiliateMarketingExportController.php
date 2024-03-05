<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AffiliateMarketingExport;

class AffiliateMarketingExportController extends Controller
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

    public function export()
    {
       
        return \Excel::download(new AffiliateMarketingExport,
            'Affiliate Marketing - '.now()->toDateTimeString().'.xlsx');
    }
}
