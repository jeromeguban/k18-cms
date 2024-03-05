<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ValidatePaymentGatewayApiKey;
use App\Processes\PaymentGatewayCallbackProcess;
use App\Rules\ValidatePaymentGatewayClientId;
use App\Rules\ValidatePaymentGatewaySignature;
use Illuminate\Http\Request;

class PaymentGatewayCallbackController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware([ValidatePaymentGatewayApiKey::class]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $request->validate([
            'req_id'    => 'required',
            'client_id' => [
                'required',
                new ValidatePaymentGatewayClientId
            ],
            'status'    => 'required',
            'signature' => [
                'required',
                new ValidatePaymentGatewaySignature
            ],
            // 'extras'    => 'required',
        ]);

        (new PaymentGatewayCallbackProcess())->handle();
    
        return [
            'success'   => 1,
            // 'data'      => $process->orders()
        ];
    }

}
