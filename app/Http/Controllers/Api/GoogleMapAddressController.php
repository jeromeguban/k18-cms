<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\GuzzleRequest;

class GoogleMapAddressController extends Controller
{
    public function index()
    {
        $request = new GuzzleRequest();

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".request()->latitude.",".request()->longtitude."&key=AIzaSyB1Zjeb3B_UappcLJTiItRB2Sw_WimxcQU";

        $response = $request->get($url);

        if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');

        if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));

        return $request->data();
    }
}
