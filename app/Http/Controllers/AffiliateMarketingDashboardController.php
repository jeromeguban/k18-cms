<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Queries\AffiliateMarketingDashboard;
use Carbon\Carbon;

class AffiliateMarketingDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       
        $query = AffiliateMarketingDashboard::query();

        return $this->response($query);
    }
}
