<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
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

        $this->authorize('list', Company::class);

        $query = Company::selectedFields()
            ->sortable();

        return $this->response($query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        $company = Company::create([
            'company_name'        => $request->company_name,
            'company_code'        => $request->company_code,
            'default_commission'  => $request->default_commission,
            'created_by'          => auth()->id(),
            'modified_by'         => auth()->id(),
        ]);


        activity()
            ->performedOn($company)
            ->withProperties($company)
            ->log('Company has been created');


        return [
            'success'   => 1,
            'data'      => $company

        ];
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);


        $company->update([
            'company_name'        => $request->company_name,
            'company_code'        => $request->company_code,
            'default_commission'  => $request->default_commission,
            'modified_by'         => auth()->id(),
        ]);


        activity()
            ->performedOn($company)
            ->withProperties($company)
            ->log('Company has been updated');


        return [
            'success'   => 1,
            'data'      => $company

        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        DB::transaction(function () use ($company) {

            $company->update([
                'deleted_by'    => auth()->id(),
            ]);

            $company->delete();

            activity()
                ->performedOn($company)
                ->withProperties($company)
                ->log('Company has been deleted');
        });

        return [
            'success'   => 1
        ];
    }
}
