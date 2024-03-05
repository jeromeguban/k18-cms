<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Courier;
use App\Models\StoreCourier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class StoreCourierController extends Controller
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
    public function index(Store $store)
    {
        $this->authorize('list', StoreCourier::class);

        $query = StoreCourier::SelectedFields()
            ->where('store_id', $store->id)
            ->joinCouriers()
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
    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);

        $request->validate([
                    'courier_id'            => [
                        'required',
                        Rule::unique('store_couriers')->where(function ($query) use($request) {
                            return $query->where('store_id', $request->store_id);
                        })
                    ],
                ],
            [],
        [
             'courier_id'    => "Courier" 
        ]
        );

        $store_courier = StoreCourier::create([
            'store_id'    => $request->store_id,
            'courier_id'  => $request->courier_id,
            'active'      => $request->active ? 1 : 0,
        ]);

        activity()
            ->performedOn($store_courier)
            ->withProperties($store_courier)
            ->log('Store Courier has been created');

        return [
            'success' => 1,
            'data' => $store_courier
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, StoreCourier $store_courier) 
    {
        $this->authorize('update', $store_courier);
        
        $store_courier->update([
            'active'   => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($store_courier)
            ->withProperties($store_courier)
            ->log('Store Courier has been updated');

        return [
            'success' => 1,
            'data' => $store_courier
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreCourier $store_courier)
    {
        $this->authorize('delete', $store_courier);

        $store_courier->delete();

        activity()
            ->performedOn($store_courier)
            ->withProperties($store_courier)
            ->log('Store Courier has been deleted');

        return ['success' => 1];
    }
}
