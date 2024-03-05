<?php

namespace App\Http\Controllers;

use App\Models\CustomerRegistration;
use App\Models\Datawarehouse\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TotalCustomerController extends Controller
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
    public function totalCustomers(Request $request)
    {
        $this->authorize('list', Customer::class);

        $query = Customer::query();

        if(empty($request->start_date) || empty($request->end_date)) {
            $query->whereBetween('created_at', [Carbon::now()->startOfDay()->toDateString(), Carbon::now()->endOfDay()->toDateString()]);
        }

        if($request->start_date || $request->end_date) {
            $query->whereBetween('created_at', [Carbon::parse(request()->start_date . ' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date . ' 23:59:59')->toDateTimeString()]);
        }

        return $query->count();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalCustomerRegistrations(Request $request)
    {
        $this->authorize('list', CustomerRegistration::class);

        $query = CustomerRegistration::query();

        if(empty($request->start_date) || empty($request->end_date)) {
            $query->whereBetween('created_at', [Carbon::now()->startOfDay()->toDateString(), Carbon::now()->endOfDay()->toDateString()]);
        }

        if($request->start_date || $request->end_date) {
            $query->whereBetween('created_at', [Carbon::parse(request()->start_date . ' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date . ' 23:59:59')->toDateTimeString()]);
        }

        return $query->count();
    }
}
