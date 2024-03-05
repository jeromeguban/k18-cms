<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Processes\PaymayaCallbackProcess;
use App\Processes\PaymentGatewayCallbackProcess;
use App\Rules\ValidatePaymentGatewayClientId;
use App\Rules\ValidatePaymentGatewaySignature;
use Illuminate\Http\Request;

class PaymayaCallbackController extends Controller
{



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        
        $request->validate([
            'requestReferenceNumber'    => 'required',
            'status'    => 'required',
        ]);


        (new PaymayaCallbackProcess())->handle();

        return [
            'success'   => 1,
            // 'data'      => $process->orders()
        ];
    }

}
