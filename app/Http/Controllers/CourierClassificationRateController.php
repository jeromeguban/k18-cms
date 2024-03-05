<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\CourierClassificationRate;
use App\Http\Requests\CourierClassificationRateRequest;

class CourierClassificationRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Courier $courier)
    {
        $this->authorize('list-rates', $courier);

        $query = CourierClassificationRate::selectedFields()
            ->searchable()
            ->sortable()
            ->when(request()->origin_classification_id && request()->destination_classification_id, function ($query) {

                $query->where('origin_classification_id', request()->origin_classification_id)
                    ->where('destination_classification_id', request()->destination_classification_id);
            }, function ($query) {

                $query->joinOriginClassification()
                    ->joinDestinationClassification();
            })
            ->where('courier_classification_rates.courier_id', $courier->id);

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
    public function store(CourierClassificationRateRequest $request,  Courier $courier)
    {

        $this->authorize('create-rates', $courier);

        DB::transaction(function () use ($courier, $request) {
            
            $courier_classification_rate = CourierClassificationRate::updateOrCreate([
                'courier_id'    => $courier->id,
                'origin_classification_id' => request()->origin_classification_id,
                'destination_classification_id' => request()->destination_classification_id,
            ], [
                'rates' => json_encode(request()->rates),
                'created_by' => auth()->id(),
                'modified_by' => auth()->id(),
            ]);

            Redis::set('courier_' . $courier->id . '_origin_' . request()->origin_classification_id . '_destination_' . request()->destination_classification_id . '_rates', json_encode($courier_classification_rate->rates));

            activity()
            ->performedOn($courier_classification_rate)
            ->withProperties($courier_classification_rate)
            ->log('Courier Classfication Rate has been created');

            return [
                'data'      => $courier_classification_rate,
                'success'   => 1
            ];
        
        });
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Courier $courier, CourierClassificationRate $classification_rate)
    {
        $this->authorize('view-rates', $courier);

        return CourierClassificationRate::where('courier_id', $courier->id)
            ->where('id', $classification_rate->id)
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
    public function update(CourierClassificationRateRequest $request, Courier $courier, CourierClassificationRate $classification_rate)
    {

        $this->authorize('update-rates', $courier);

        abort_if($courier->id != $classification_rate->courier_id, 403, "Invalid Courier Classfication Rate");

        $classification_rate->update([
            'courier_id'    => $courier->id,
            'origin_classification_id' => request()->origin_classification_id,
            'destination_classification_id' => request()->destination_classification_id,
            'rates' => json_encode(request()->rates),
            'modified_by' => auth()->id(),
        ]);


        activity()
            ->performedOn($classification_rate)
            ->withProperties($classification_rate)
            ->log('Courier Classfication Rate has been updated');

        return [
            'data'      => $classification_rate,
            'success'   => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Courier $courier, CourierClassificationRate $classification_rate)
    {
        $this->authorize('delete-rates', $courier);

        abort_if($courier->id != $classification_rate->courier_id, 403, "Invalid Courier Classfication Rate");

        \DB::transaction(function () use ($classification_rate) {
            $classification_rate->update([
                'deleted_by'    => auth()->id()
            ]);

            $classification_rate->delete();

            activity()
                ->performedOn($classification_rate)
                ->withProperties($classification_rate)
                ->log('Courier Classfication Rate has been deleted');
        });

        return [
            'success'   => 1
        ];
    }
}
