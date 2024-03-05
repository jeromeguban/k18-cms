<?php

namespace App\Http\Controllers;

use App\Models\CostType;
use Illuminate\Http\Request;
use App\Http\Requests\CostTypeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CostTypeController extends Controller
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

        $this->authorize('list', CostType::class);

        $query = CostType::selectedFields()
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
    public function store(CostTypeRequest $request)
    {
        $this->authorize('create', CostType::class);

        $cost_type = CostType::create([
            'type'          => $request->type,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id(),
        ]);


        activity()
            ->performedOn( $cost_type )
            ->withProperties($cost_type)
            ->log('Cost Type has been created');


        return [
            'success'   => 1,
            'data'      => $cost_type

        ];

    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CostType  $cost_type
     * @return \Illuminate\Http\Response
     */
    public function update(CostTypeRequest $request, CostType $cost_type)
    {
        $this->authorize('update', $cost_type);

    
        $cost_type->update([
            'type'          => $request->type,
            'modified_by'   => auth()->id(),
        ]);


        activity()
            ->performedOn( $cost_type )
            ->withProperties($cost_type)
            ->log('Cost Type has been updated');


        return [
            'success'   => 1,
            'data'      => $cost_type

        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostType  $cost_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostType $cost_type)
    {
        $this->authorize('delete', $cost_type);

        DB::transaction(function() use ($cost_type) {

            $cost_type->update([
                'deleted_by'    => auth()->id(),
            ]);

            $cost_type->delete();

            activity()
                ->performedOn( $cost_type )
                ->withProperties($cost_type)
                ->log('Cost Type has been deleted');
        });

        return [
            'success'   => 1
        ];
    }
}
