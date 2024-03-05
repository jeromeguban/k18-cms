<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForPayableController extends Controller
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
        
        $query = Company::select([
                    'companies.company_name',
                    'companies.company_code',
                    'companies.default_commission',
                ])
                ->joinForPayables($from, $to)
                ->when(request()->company_id, function($query){
                   $query->where('companies.id', request()->company_id);
                });

        return $this->response($query);
    }

}
      