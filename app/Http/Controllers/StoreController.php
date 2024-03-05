<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Processes\StoreProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Str;use Illuminate\Validation\Rule;

class StoreController extends Controller
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
        $this->authorize('list', Store::class);

        // Select All Data if user has access on all Stores
        // $all_store = $this->authorize('all', Store::class) ? true : false;

        // if (!$all_store) {

        //     $query = Store::selectedFields()
        //         ->joinCompany()
        //         ->withRelations()
        //         ->searchable()
        //         ->sortable();

        //     if (request()->store_type == 'Retail Warehouse' || request()->store_type == 'Retail') {
        //         $query->where('store_type', 'HMR Retail Haus')
        //             ->orWhere('store_type', 'Save On Surplus');
        //     }

        //     if (request()->store_type == 'Auction Warehouse' || request()->store_type == 'Auction') {
        //         $query->where('store_type', 'Auction Warehouse');
        //     }

        // } else {

        //     $query = Store::selectedFields()
        //         ->joinCompany()
        //         ->withRelations()
        //         ->searchable()
        //         ->sortable();

        // }

        $query = Store::selectedFields()
            ->leftjoinClassifications()
            ->leftJoinCompany()
            ->withRelations()
            ->searchable()
            ->sortable();

        if (request()->store_type == 'Retail store' || request()->store_type == 'Retail') {
            $query->where('store_type', 'HMR Retail Haus')
                ->orWhere('store_type', 'Save On Surplus');
        }

        if (request()->store_type == 'Auction store' || request()->store_type == 'Auction') {
            $query->where('store_type', 'Auction store');
        }

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
    public function store(Request $request, StoreProcess $process)
    {
        $this->authorize('create', Store::class);

        $request->validate([
            'code' => 'required|unique:stores',
            // 'store_company_name'    => 'required',
            'store_name' => 'required',
            'address_line' => 'required',
            'extended_address' => 'nullable',
            'contact_number' => 'nullable|numeric',
            'email' => 'nullable',
            'store_type' => 'required',
            'description' => 'nullable',
            'store_company_code' => 'required',
            'company_id' => 'required',
            'classification_id' => 'nullable',
            'couriers' => 'required|array',
            'couriers.*.id' => 'required|exists:couriers,id',
        ]);

        $process->create();

        activity()
            ->performedOn($process->store())
            ->withProperties($process->store())
            ->log('Store has been created');

        return [
            'success' => 1,
            'data' => $process->store(),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $this->authorize('view', $store);

        return Store::where('id', $store->id)
            ->withRelations()
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
    public function update(Request $request, Store $store, StoreProcess $process)
    {
        $this->authorize('update', $store);

        $request->validate([
            'code' => [
                'required',
                Rule::unique('stores')->ignore($store->id),
            ],
            // 'store_company_name'=> 'required',
            'store_name' => 'required',
            'address_line' => 'required',
            'extended_address' => 'nullable',
            'contact_number' => 'nullable|numeric',
            'email' => 'nullable',
            'store_type' => 'required',
            'description' => 'nullable',
            'store_company_code' => 'required',
            'company_id' => 'required',
            'couriers' => 'required|array',
            'couriers.*.id' => 'required|exists:couriers,id',
            'classification_id' => 'nullable',
        ]);

        $process->find($store->id)->update();

        activity()
            ->performedOn($process->store())
            ->withProperties($process->store())
            ->log('Store has been updated');

        return [
            'success' => 1,
            'data' => $process->store(),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);

        $store->delete();

        activity()
            ->performedOn($store)
            ->withProperties($store)
            ->log('Store has been deleted');

        return ['success' => 1];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActive(Request $request, Store $store)
    {
        $this->authorize('update', $store);

        $store->update([
            'active' => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($store)
            ->withProperties($store)
            ->log('Store has been updated');

        return [
            'success' => 1,
            'data' => $store,
        ];
    }
}
