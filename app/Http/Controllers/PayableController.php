<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\OrderPosting;
use App\Models\Payable;
use App\Processes\PayableSubmissionProcess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PayableController extends Controller
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
        $this->authorize('list', Payable::class);

        $query = Payable::selectedFields()
                    ->leftJoinBank()
                    ->leftJoinAccountNumber()
                    ->joinCompany()
                    ->joinTotalPayables()
                    ->withRelations()
                    ->searchable()
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
    public function store(Request $request, PayableSubmissionProcess $process)
    {
        $request->validate([
            'company_id'     => [
                'required',
                'exists:companies,id'
            ],
            'remarks'       => 'nullable',
            'costs'         => 'nullable|array',
            'costs.*.id'    => [
                'nullable',
                Rule::exists('costs')->where(function($query){
                    $query->whereNull('payable_id');
                })
            ],
            'excluded_items'    => [
                'nullable',
            ]
        ],[],[
            'company_id'        => 'Company',
            'remarks'           => 'Remarks',
            'excluded_items'    => 'Excluded Items',
        ]);

        
        $process->create();

        activity()
            ->performedOn( $process->payable() )
            ->withProperties($process->payable())
            ->log('Payable has been created');

        return [
            'success'   => 1,
            'data'      => $process->payable()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payable $payable)
    {

        $this->authorize('view', $payable);

        $payable =  Payable::selectedFields()
                        ->joinTotalPayables()
                        ->joinCompany()
                        ->where('payables.id', $payable->id)
                        ->first();

         $payable_stores =  Payable::selectedFields()
                                ->joinPayablesPerStore()
                                ->where('payables.id', $payable->id)
                                ->get();

        return [
            'payables'  => $payable,
            'payable_stores'  => $payable_stores
        ];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payable $payable)
    {
        $this->authorize('update', $payable);

        $payable->update([
            'remarks'            => $request->remarks,
            'check_number'       => $request->check_number,
            'check_date'         => Carbon::parse($request->check_date)->toDateString(),
            'bank_id'            => $request->bank_id,
            'account_number_id'  => $request->account_number_id,
            'status'             => $request->status,
            'check_issued_by'    => auth()->id(),
            'modified_by'        => auth()->id(),
        ]);

        activity()
            ->performedOn( $payable )
            ->withProperties($payable)
            ->log('Payable has been updated');

        return [
            'success' => 1,
            'data' => $payable
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payable $payable)
    {
        $this->authorize('delete', $payable);

        if ($payable->status == "Approved")
            abort(422, "Payable is Already Approved.");

        DB::transaction(function() use ($payable) {

            OrderPosting::where('payable_id', $payable->id)
                ->update([ 
                    'payable_id' => null,
                ]);
           
            Cost::where('payable_id', $payable->id)
                ->update([ 
                    'payable_id' => null,
                ]); 
            

            $payable->update([
                'deleted_by'   => auth()->id(),
            ]);

            $payable->delete();


            activity()
                ->performedOn( $payable )
                ->withProperties($payable)
                ->log('Payable has been deleted');
        
        });
        
        return [
            'success'   => 1,
        ];
    }
}
