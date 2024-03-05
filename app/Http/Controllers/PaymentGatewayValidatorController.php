<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BidderDeposit;
use App\Models\Order;
use App\Models\PaymentType;
use App\Processes\OrderPaymentProcess;
use App\Processes\PaymayaCallbackProcess;
use App\Processes\PaymentGatewayCallbackProcess;
use Illuminate\Support\Facades\DB;

class PaymentGatewayValidatorController extends Controller
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

    public function index(Request $request)
    {
      $validate_reference_code = explode('-', $request->ref_code);

        $type = count($validate_reference_code) == 1 ? 'order' : (count($validate_reference_code) == 2 ? 'bidder_deposit' : null);

        $reference = null;

        switch($type) {
            case 'order':
                $reference = Order::selectedFields()
                                ->where('orders.reference_code', $request->ref_code)
                                ->orderBy('orders.created_at', 'DESC')
                                ->first();
            break;

            case 'bidder_deposit':
                $reference = BidderDeposit::selectedFields()
                                ->where('bidder_deposits.reference_code', $request->ref_code)
                                ->first();
            break;

        }

       


        $payment_type = PaymentType::where('id', $reference->payment_type_id)
                            ->where('status', 1)
                            ->first();

        if(!$payment_type) 
             abort(422, "No Payment Type found on Order.");


        try {
            $payment_details = (new OrderPaymentProcess(['payment_type_id' => $reference->payment_type_id]))
                                ->setReferenceCode($request->ref_code)
                                ->getPaymentGatewayDetails();
        } catch(\Exception $e) {
            $payment_details = null;
        }

        if(!$payment_details)
            abort(422, "No Transaction found.");

        if($payment_details['status'] == 'Expired')
            abort(422, "Expired");

        if($payment_details['status'] == 'Pending')
            abort(422, "Pending");

        
        switch($payment_type->type) {
            case 'Bux':
                $request = [
                    'req_id'    => $request->ref_code,
                    'status'    => strtolower($payment_details['status']),
                    'extras'    => [
                        'fee' => $payment_details['fee']
                    ]
                ];

                $process = (new PaymentGatewayCallbackProcess($request))
                                ->handle();
            break;
            case 'Paymaya':
                $process = (new PaymayaCallbackProcess($payment_details->toArray()))
                                ->handle();
            break;
        }

        
       return [
            'success' =>  1,
            'data'    =>  $request
        ];

    }
  
}
