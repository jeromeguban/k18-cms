<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreAddress;
use App\Models\StoreAdrress;
use App\Helpers\ModelDecrypter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreAddressesController extends Controller
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
    public function index(Store $store)
    {
        $this->authorize('list', StoreAddress::class);

        $query = StoreAddress::selectedFields()
                ->where('store_id', $store->id)
                ->searchable()
                ->sortable();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($store_address) {
            $store_address = (new ModelDecrypter)->decryptModel($store_address);
        });

        return $response;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', StoreAddress::class);

        $request->validate([
            // 'store_id'                          => 'required|unique:store_addresses,store_id',
            'contact_person'                    => 'required',
            'contact_number'                    => [
                'required',
                'regex:/^(639|09)\d{9}$/',
            ],
            'email'                             => 'required|email',
            'additional_information'            => 'required',
            // 'country'                           => 'required',
            'province'                          => 'required',
            'city'                              => 'required',
            'barangay'                          => 'required',
            'zipcode'                           => 'required',
        ],[],[
            'contact_person'                    => 'Contact Person',
            'contact_number'                    => 'Contact Number',
            'additional_information'            => 'Additional Information ',
            'country'                           => 'Country',
            'province'                          => 'Province',
            'city'                              => 'City',
            'barangay'                          => 'Barangay',
            'zipcode'                           => 'Zipcode',
            'google_places_id'                  => 'Google Place Id',
            'latitude'                          => 'Latitude',
            'longitude'                         => 'Longitude',
            'store_id'                          => 'Store',
        ]);

        $store_address = StoreAddress::create([
            'store_id'               => $request->store_id,
            'contact_person'         => encrypt(strtoupper($request->contact_person)),
            'contact_number'         => encrypt($request->contact_number),
            'email'                  => encrypt(strtolower($request->email)),
            'country'                => 'Philippines',
            'province'               => $request->province,
            'city'                   => $request->city,
            'barangay'               => $request->barangay,
            'zipcode'                => $request->zipcode,
            'additional_information' => $request->additional_information,
            'classification_id'      => null,
            'google_places_id'       => $request->google_places_id,
            'latitude'               => $request->latitude,
            'longitude'              => $request->longitude,
            'created_by'             => auth()->id(),
            'modified_by'            => auth()->id(),
        ]);

        activity()
        ->performedOn($store_address)
        ->withProperties($store_address)
        ->log('Address has been created');

        return [
            'data'      => $store_address,
            'success'   => 1
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreAdrress  $storeAdrress
     * @return \Illuminate\Http\Response
     */
    public function show(StoreAdrress $storeAdrress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreAdrress  $storeAdrress
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreAdrress $storeAdrress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreAdrress  $storeAdrress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreAddress $store_address)
    {
        $this->authorize('update', $store_address);

        $request->validate([
            // 'store_id'                          => [
            //     'required',
            //     Rule::unique('store_addresses')->ignore($store_address->address_id, 'address_id'),
            // ],
            'contact_person'                    => 'required',
            'contact_number'                    => [
                'required',
                'regex:/^(639|09)\d{9}$/',
            ],
            'email'                             => 'required|email',
            'additional_information'            => 'required',
            // 'country'                           => 'required',
            'province'                          => 'required',
            'city'                              => 'required',
            'barangay'                          => 'required',
            'zipcode'                           => 'required',
        ],[],[
            'contact_person'                    => 'Contact Person',
            'contact_number'                    => 'Contact Number',
            'additional_information'            => 'Additional Information ',
            'country'                           => 'Country',
            'province'                          => 'Province',
            'city'                              => 'City',
            'barangay'                          => 'Barangay',
            'zipcode'                           => 'Zipcode',
            'google_places_id'                  => 'Google Place Id',
            'latitude'                          => 'Latitude',
            'longitude'                         => 'Longitude',
            'store_id'                          => 'Store',
        ]);

        $store_address->update([
            'store_id'               => $request->store_id,
            'contact_person'         => encrypt(strtoupper($request->contact_person)),
            'contact_number'         => encrypt($request->contact_number),
            'email'                  => encrypt(strtolower($request->email)),
            'country'                => 'Philippines',
            'province'               => $request->province,
            'city'                   => $request->city,
            'barangay'               => $request->barangay,
            'zipcode'                => $request->zipcode,
            'additional_information' => $request->additional_information,
            'classification_id'      => null,
            'google_places_id'       => $request->google_places_id,
            'latitude'               => $request->latitude,
            'longitude'              => $request->longitude,
            'modified_by'            => auth()->id(),
        ]);

        activity()
            ->performedOn($store_address)
            ->withProperties($store_address)
            ->log('Address has been updated');


        return [
            'data'      => $store_address,
            'success'   => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreAdrress  $storeAdrress
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreAddress $store_address)
    {
        $this->authorize('delete', $store_address);

        $store_address->delete();

        activity()
            ->performedOn($store_address)
            ->withProperties($store_address)
            ->log('Address has been deleted');

        return ['success' => 1];
    }

    public function list()
    {
        $this->authorize('list', StoreAddress::class);

        $query = StoreAddress::selectedFields()
                ->where('store_id', session()->get('store_id'))
                ->searchable()
                ->sortable();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($store_address) {
            $store_address = (new ModelDecrypter)->decryptModel($store_address);
        });

        return $response;
    }

}
