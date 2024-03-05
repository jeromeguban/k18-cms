<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryStatuses;
use Illuminate\Validation\Rule;

class DeliveryStatusesController extends Controller
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
        $this->authorize('list', DeliveryStatuses::class);

        $query = DeliveryStatuses::withRelations()
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
        $this->authorize('create', DeliveryStatuses::class);

        $request->validate([
            'code'          => 'required|unique:delivery_statuses,code',
            'description'   => 'required',
        ], [], [
            'code'          => 'Code',
            'description'   => 'Description'
        ]);

        $delivery_statuses = DeliveryStatuses::create([
            'code'          => $request->code,
            'description'   => $request->description,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id(),
        ]);

        activity()
            ->performedOn($delivery_statuses)
            ->withProperties($delivery_statuses)
            ->log('Delivery Statuses has been Created');

        return [
            'success'   => 1,
            'data'      => $delivery_statuses
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryStatuses $delivery_status)
    {
        $this->authorize('view', $delivery_status);
        
        return DeliveryStatuses::where('id', $delivery_status->id)
                        ->first();
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
    public function update(Request $request, $id, DeliveryStatuses $deliveryStatuses)
    {
        $this->authorize('update', $deliveryStatuses);

        $request->validate([
            'code'      => [
                'required',
                Rule::unique('delivery_statuses', 'code')->ignore(request()->id, 'id')
            ],
            'description'   => 'required',
        ]);

        $deliveryStatuses->update([
            'code'          => $request->code,
            'description'   => $request->description,
            'modified_by'   => auth()->id(),
        ]);

        activity()
            ->performedOn($delivery_statuses)
            ->withProperties($delivery_statuses)
            ->log('Delivery Statuses has been updated');

        return [
            'success'   => 1,
            'data'      => $delivery_statuses
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryStatuses $delivery_status)
    {
        $this->authorize('delete', $delivery_status);

        $delivery_status->delete();

        activity()
            ->performedOn($delivery_status)
            ->withProperties($delivery_status)
            ->log('Delivery Status has been deleted');

        return [
            'success'   => 1
        ];
    }
}
