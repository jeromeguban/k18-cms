<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\GuzzleRequest;

class GoogleMapAddressController extends Controller
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
        $request = new GuzzleRequest();

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".request()->latitude.",".request()->longitude."&key=AIzaSyB1Zjeb3B_UappcLJTiItRB2Sw_WimxcQU";

        $response = $request->get($url);

        if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');

        if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));

        return $request->data();
    }

}
