<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GuzzleRequest;

class StreamCountController extends Controller
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
    public function index($stream)
    {

        $client_secret = env('AMS_JWT_SECRET');

        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Create token payload as a JSON string
        $payload = json_encode(new \stdClass());

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $client_secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $form = [
            'headers' => [
                'Authorization' => $jwt,
            ],
        ];




        $request = new GuzzleRequest($form);

        $response = $request->get(env('AMS_REST_URL').'/v2/broadcasts/'.$stream.'/broadcast-statistics');

        if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');

        if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));
        
        $total_watcher_count = (int) $request->data()['totalWebRTCWatchersCount'] 
            + (int) $request->data()['totalHLSWatchersCount'] 
            + (int) $request->data()['totalDASHWatchersCount'];
            
        return [
            'data'  => [
                'count' => $total_watcher_count
            ],
            'success' => 1
        ];
    }

}
