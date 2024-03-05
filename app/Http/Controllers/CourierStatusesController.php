<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourierStatuses;
use Illuminate\Validation\Rule;

class CourierStatusesController extends Controller
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
        $this->authorize('list', CourierStatuses::class);

        $query = CourierStatuses::selectedFields()
                                ->whereQueryScopes()
                                ->joinDeliveryStatus()
                                ->joinCouriers()
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
    public function store(Request $request)
    {
        $request->validate([
            'status_id'     => 'required',
            'courier_id'    => 'required',
            'status_code'   => [
                'required',
                Rule::unique('courier_statuses')->ignore(request()->id, 'id')
                    ->where(function ($query) {
                        return $query->where('courier_id', request()->courier_id);
                    })
            ],
            'description'   => 'required',
        ], [], [
            'status_id'     => 'Inhouse Status',
            'courier_id'    => 'Courier',
            'status_code'   => 'Courier Status Code',
            'description'   => 'Description',
        ]);

        $courier_status = CourierStatuses::create([
            'status_id'     => $request->status_id,
            'courier_id'    => $request->courier_id,
            'status_code'   => $request->status_code,
            'description'   => $request->description,
        ]);

        activity()
            ->performedOn( $courier_status)
            ->withProperties( $courier_status)
            ->log('Courier Status has been Created');

        return [
            'success'   => 1,
            'data'      => $courier_status
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id, CourierStatuses $courier_status)
    {
        $this->authorize('update', $courier_status);

        $request->validate([
            'status_id'     => 'required',
            'courier_id'    => 'required',
            'status_code'   => [
                'required',
                Rule::unique('courier_statuses')->ignore(request()->id, 'id')
                    ->where(function ($query) {
                        return $query->where('courier_id', request()->courier_id);
                    })
            ],
            'description'   => 'required'
        ], [], [
            'status_id'     => 'Inhouse Status',
            'courier_id'    => 'Courier',
            'status_code'   => 'Courier Status Code',
            'description'   => 'Description',
        ]);

        $courier_status->update([
            'status_id'         => $request->status_id,
            'status_code'      => $request->status_code,
            'description'       => $request->description,
        ]);

        activity()
            ->performedOn( $courier_status)
            ->withProperties( $courier_status)
            ->log('Courier Status has been updated');

        return [
            'success'   => 1,
            'data'      => $courier_status
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourierStatuses $courier_status)
    {
        $this->authorize('delete', $courier_status);

        $courier_status->delete();

        activity()
            ->performedOn($courier_status)
            ->withProperties($courier_status)
            ->log('Courier Status has been deleted');

        return [
            'success'   => 1
        ];
    }
}
