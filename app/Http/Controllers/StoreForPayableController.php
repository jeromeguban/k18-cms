<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreForPayableController extends Controller
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
    public function index()
    {
        $this->authorize('create', Payable::class);

        $from = Carbon::parse(request()->from.' 00:00:00')->toDateTimeString();
        $to = request()->to ? Carbon::parse(request()->to.' 23:59:59')->toDateTimeString() : now()->toDateTimeString();
        
        $excluded_items = request()->excluded_items ?? [];

        $query = Store::selectedFields()
                ->joinForPayablesPerStore($from, $to, $excluded_items)
                ->where('stores.company_id', request()->company_id);

        return $this->response($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $this->authorize('create', Payable::class);

        $from = Carbon::parse(request()->from.' 00:00:00')->toDateTimeString();
        $to = request()->to ? Carbon::parse(request()->to.' 23:59:59')->toDateTimeString() : now()->toDateTimeString();
        $excluded_items = request()->excluded_items ?? [];

        return Store::selectedFields()
                ->joinForPayablesPerStore($from, $to, $excluded_items)
                ->where('stores.id', $store->id)
                ->first();
    }

}
