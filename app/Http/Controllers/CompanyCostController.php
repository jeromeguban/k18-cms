<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyCostController extends Controller
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
    public function index(Company $company)
    {
        $this->authorize('list', Cost::class);

        $query = Cost::selectedFields()
            ->joinCompany()
            ->sortable()
            ->where('costs.company_id', $company->id);

        if (request()->for_payables)
            $query->whereForPayables();
            
        $query->whereNull('costs.payable_id');

        return $this->response($query);
    }


}
