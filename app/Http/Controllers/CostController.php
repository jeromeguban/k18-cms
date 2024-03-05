<?php

namespace App\Http\Controllers;


use App\Models\Cost;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\CostRequest;
use Illuminate\Support\Facades\DB;

class CostController extends Controller
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
        $this->authorize('list', Cost::class);

        $query = Cost::selectedFields()
            ->joinStore()
            ->joinCostType()
            ->whereNull('costs.payable_id')
            ->withRelations()
            ->searchable()
            ->sortable();

        if (request()->type) {

            $query = Cost::selectedFields()
                ->joinVendor()
                ->joinCostType()
                ->joinPayable()
                ->whereNotNull('costs.payable_id')
                ->withRelations()
                ->searchable()
                ->sortable();
        }


        return $this->response($query);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostRequest $request)
    {

        $this->authorize('create', Cost::class);

        $company = Company::where('id', $request->company_id)->first();
       
        $cost = Cost::create([
        'company_id'             => $company->id,
        'store_id'               => $request->store_id,
        'store_company_code'     => $company->company_code,
        'cost_type_id'           => $request->cost_type_id,
        'amount'                 => $request->amount,
        'remarks'                => $request->remarks,
        'created_by'             => auth()->id(),
        'modified_by'            => auth()->id()
        ]);

        activity()
            ->performedOn($cost)
            ->withProperties($cost)
            ->log('Cost has been created');

        return [
            'success'   => 1,
            'data'      => $cost
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cost  $cost
     * @return \Illuminate\Http\Response
     */
    public function update(CostRequest $request, Cost $cost)
    {

        $this->authorize('update', $cost);

        abort_unless(!$cost->payable_id, 422, 'Payable already issued for this cost');

        $company = Company::where('id', $request->company_id)->first();

        $cost->update([
            'company_id'              => $company->id,
            'store_id'                => $request->store_id,
            'store_company_code'      => $company->company_code,
            'cost_type_id'            => $request->cost_type_id,
            'amount'                  => $request->amount,
            'remarks'                 => $request->remarks,
            'modified_by'             => auth()->id(),
        ]);

        activity()
            ->performedOn($cost)
            ->withProperties($cost)
            ->log('Cost has been updated');

        return [
            'success'   => 1,
            'data'      => $cost
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cost  $cost
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cost $cost)
    {
        $this->authorize('delete', $cost);

        abort_unless(!$cost->payable_id, 403, 'Payable already issued for this cost');

        DB::transaction(function () use ($cost) {

            $cost->delete();

            activity()
                ->performedOn($cost)
                ->withProperties($cost)
                ->log('Cost has been deleted');
        });

        return [
            'success'   => 1
        ];
    }
}
