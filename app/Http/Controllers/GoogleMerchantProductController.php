<?php

namespace App\Http\Controllers;

use App\Models\GoogleMerchantProduct;
use Illuminate\Http\Request;

class GoogleMerchantProductController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($google_merchant_product)
    {
        return GoogleMerchantProduct::where('id', $google_merchant_product)->first();
    }

   
}
