<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreValidateController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Store $store)
    {
        $request->session()->put('store_id', $store->id);
        $request->session()->put('store_name', $store->store_name);
        $request->session()->put('reference_id', $store->reference_id);

        return [
            'success' => 1
        ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        session()->forget('store_id');
        session()->forget('store_name');
        session()->forget('reference_id');
    
        return [
            'success' => 1
        ];

        // return redirect('/settings/store');

    }

}
