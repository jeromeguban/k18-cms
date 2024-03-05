<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPosting;
use App\Models\Posting;
use App\Queries\OrderTotalResult;
use App\Queries\OrderDateTotalResult;
use Carbon\Carbon;

class OrderTotalController extends Controller
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
        $query = OrderTotalResult::query();

        return $this->response( $query);
    }
}
