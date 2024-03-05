<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourierRequest;
use App\Models\StoreCourier;
use App\Processes\CourierIconProcess;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
    public function index(Courier $courier)
    {
        $this->authorize('list', $courier);

        $query = Courier::SelectFields()
            ->withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CourierRequest $request, Courier $courier)
    {

        $this->authorize('create', $courier);

        DB::transaction( function () use ($courier, $request) {

            $courier = Courier::create([
                'name'           =>  $request->name,
                'vat'            =>  $request->vat ?? 0,
                'active'         =>  $request->active ? 1 : 0,
                'created_by'     => auth()->id(),
                'modified_by'    => auth()->id(),
            ]);

            if($request->file('icon'))
                (new CourierIconProcess($courier, $request->file('icon')))->upload();

            activity()
                ->performedOn($courier)
                ->withProperties($courier)
                ->log('Courier has been created');

            return [
                'success' => 1,
                'data' => $courier
            ];

        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Courier $courier)
    {
        $this->authorize('view', $courier);

        return Courier::where('id', $courier->id)
            ->withRelations()
            ->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourierRequest $request, Courier $courier)
    {
        $this->authorize('update', $courier);

        DB::transaction(function () use ($courier, $request) {

            $courier->update([
                'name'   =>  $request->name,
                'vat'    =>  $request->vat ?? 0,
                'active' =>  $request->active ? 1 : 0,
                'modified_by' => auth()->id()
            ]);

            activity()
                ->performedOn($courier)
                ->withProperties($courier)
                ->log('Courier has been updated');

            return [
                'success' => 1,
                'data' => $courier
            ];

        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setIcon(Request $request, Courier $courier)
    {
        $this->authorize('update', $courier);

        DB::transaction(function () use ($courier, $request) {

            if ($request->file('icon')) 
                 (new CourierIconProcess($courier, $request->file('icon')))->upload();

            activity()
                ->performedOn($courier)
                ->withProperties($courier)
                ->log('Courier Icon has been updated');

            return [
                'success' => 1,
                'data' => $courier
            ];
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Courier $courier)
    {
        $this->authorize('update', $courier);

        $courier->update([
            'active'   => $request->active == 0 ? 1 : 0,
        ]);

        StoreCourier::where('courier_id', $courier->id)
            ->update([
                'active'   => $request->active == 0 ? 1 : 0,
            ]);

        activity()
            ->performedOn($courier)
            ->withProperties($courier)
            ->log('Courier has been updated');

        return [
            'success' => 1,
            'data' => $courier
        ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Courier $courier)
    {
        $this->authorize('delete', $courier);

        $courier->delete();

        activity()
            ->performedOn($courier)
            ->withProperties($courier)
            ->log('Courier has been deleted');

        return [
            'success' => 1,
            'data' => $courier
        ];
    }
}
