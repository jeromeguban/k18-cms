<?php

namespace App\Helpers;

use App\Helpers\GuzzleRequest;
use App\Helpers\ModelDecrypter;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderPosting;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BuxHelper
{
	protected $reference_code, $request, $response, $payment_type, $payment_transaction, $order_details, $store_type;

    protected $non_online_payment_channels = [
        'OP'
    ];

    protected $expiry = 24; //Hours


     /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null, $payment_type)
    {
        $this->request = $request ? (object) $request : request();
        $this->payment_type = $payment_type;

    }


    /**
     * Execute find process.
     *
     * @return void
    */

    public function setReferenceCode($reference_code)
    {
        $this->reference_code = $reference_code;
        return $this;

    }

    /**
     * Execute find process.
     *
     * @return void
    */

    public function setStoreType($store_type)
    {
        $this->store_type = $store_type;
        return $this;

    }


    /**
     * Retrive Payment Transaction.
     *
     * @return 
    */

    public function paymentTransaction()
    {
        return $this->payment_transaction;
    }

     /**
     * Retrive Payment Gateway Details.
     *
     * @return 
    */

    public function getPaymentGatewayDetails()
    {
        $form = [
            'headers' => [
                'x-api-key'   => env('BUX_PAYMENT_GATEWAY_API_KEY'),
            ],
            'query' => [
                'req_id'    => $this->reference_code,
                'client_id' =>  env('BUX_PAYMENT_GATEWAY_CLIENT_ID'),
                'mode'      => 'API'
            ]
        ];

        $request = new GuzzleRequest($form);
        $response = $request->get(env('BUX_PAYMENT_GATEWAY_PAYMENT_DETAILS_URL'));

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), 'Error in Payment Method request.');


        return collect((object) $request->data());
    }
    
    /**
     * Execute transact process.
     *
     * @return void
    */

    public function transact()
    {
        $this->getOrderDetails();

        if($this->validPaymentTypeLimit()) {
            DB::transaction(function(){

                $this->deleteExistingPaymentTransaction();
                if(!in_array($this->payment_type->code, $this->non_online_payment_channels)) {
                    $this->setPaymentGatewayTransaction()
                        ->createPaymentTransaction();
                }

                $this->updateOrderPaymentType();

            });

            return;
        }

     
        $limit_description =  "Payment channel only accepts trasanctions ".($this->payment_type->minimum_limit > 0 ? 'not lower than ₱'. number_format($this->payment_type->minimum_limit, 2, '.', ',') : '').''.($this->payment_type->minimum_limit > 0 && $this->payment_type->maximum_limit ? ' but ' : '').''.($this->payment_type->maximum_limit && $this->payment_type->maximum_limit ? ' not greater than ₱'. number_format($this->payment_type->maximum_limit, 2, '.', ',') : '')."."; 

        abort(403, $limit_description);
    }


    private function updateOrderPaymentType()
    {

        Order::where('reference_code', $this->reference_code)
            ->where('customer_id', auth()->user()->customer_id)
            ->update([
                'payment_type_id'                   => $this->payment_type->id,
                'payment_gateway_reference_code'    => $this->response ? $this->response->ref_code : null,
            ]);


        return $this;
    }

    private function createPaymentTransaction()
    {

        $this->payment_transaction = PaymentTransaction::create([
            'reference_code'                    => $this->reference_code,
            'payment_gateway_reference_code'    => $this->response->ref_code,
            'customer_id'                       => auth()->user()->customer_id,
            'transaction_status'                => 'Pending',
            'status'                            => $this->response->status,
            'expiry'                            => Carbon::parse($this->response->expiry)->toDateTimeString(),
            'payment_url'                       => $this->response->payment_url,
            'image_url'                         => $this->response->image_url,
            'payment_type_id'                   => $this->payment_type->id,
            'transaction_response'              => json_encode($this->response),
            'transaction_type'                  => 'Bux' 
        ]);

        return $this;

    }

    public function deleteExistingPaymentTransaction()
    {
        $payment_transaction = PaymentTransaction::where('reference_code', $this->reference_code)
                ->delete();
        try {
            $previous_payment_transaction = $payment_transaction ? $this->getPaymentGatewayDetails() : null;
        } catch (\Exception $e) {
            $previous_payment_transaction = null;
        }

        if($payment_transaction && $previous_payment_transaction && strtolower($previous_payment_transaction['status']) == 'pending') {
            
            $form = [
                'headers' => [
                    'x-api-key'   => env('BUX_PAYMENT_GATEWAY_API_KEY'),
                ],
                'query' => [
                    'req_id'    => $this->reference_code,
                    'client_id' =>  env('BUX_PAYMENT_GATEWAY_CLIENT_ID'),
                    'reason'      => 'Change Payment Method'
                ]
            ];

            $request = new GuzzleRequest($form);
            $response = $request->post(env('BUX_PAYMENT_GATEWAY_CANCEL_TRANSACTION_URL'));
            
            if($response->getStatusCode() != 200 && $response->getStatusCode() != 401)
                abort($response->getStatusCode(), 'Error in cancellation of previous Payment Method request.');
        }

        return $this;
    }

    private function setPaymentGatewayTransaction()
    {

        $form = [
            'headers' => [
                'x-api-key'   => env('BUX_PAYMENT_GATEWAY_API_KEY'),

            ],
            'json' => $this->getPaymentDetails()
        ];

        $request = new GuzzleRequest($form);
        $response = $request->post(env('BUX_PAYMENT_GATEWAY_API_URL'));
        if($response->getStatusCode() != 200){
            abort(403, 'Payment method unreachable. Please use another payment option.');
        }
        

        $this->response = (object) $request->data();

        return $this;

    }

    private function getPaymentDetails()
    {

        $customer = Customer::selectedFields()
                        ->addSelect([
                            'customer_login_credentials.email',
                            'customer_login_credentials.mobile_no'
                        ])
                        ->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'customers.customer_id')
                        ->where('customers.customer_id', auth()->user()->customer_id)
                        ->whereNull('customer_login_credentials.blocked_date')
                        ->first();

        $customer = (new ModelDecrypter)->decryptModel($customer);
              

        return [
            'req_id'            => $this->reference_code,
            'client_id'         => env('BUX_PAYMENT_GATEWAY_CLIENT_ID'),
            'amount'            => (float)$this->order_details->total_order_amount,
            'description'       => 'Transaction for Reference Code - '.$this->reference_code,
            'expiry'            => $this->expiry,
            'email'             => $customer->email,
            'contact'           => $customer->mobile_no,
            'channel'           => $this->payment_type->code,
            'name'              => $customer->customer_firstname.' '.$customer->customer_lastname,
            'notification_url'  => env('BUX_PAYMENT_GATEWAY_CALLBACK_URL'),
            'redirect_url'      => env('APP_URL', 'https://hmr.ph').'/payments/'.$this->reference_code.'/success',
            'cust_shoulder'     => $this->payment_type && $this->payment_type->payment_channel_group == 'Card Payments' ? 0 :1,
            'param1'			=> $this->store_type
        ];  

    }

    private function validPaymentTypeLimit()
    {
        if($this->payment_type->minimum_limit == 0 && $this->payment_type->maximum_limit == 0)
            return true;

        return $this->payment_type->minimum_limit <= $this->order_details->total_order_amount 
                    && ((!$this->payment_type->maximum_limit || $this->payment_type->maximum_limit == 0.00) || ($this->payment_type->maximum_limit >= $this->order_details->total_order_amount));
    }

    private function getOrderDetails(): self
    {
        $this->order_details =  OrderPosting::select([
                                    'order_postings.reference_code',
                                    \DB::raw('SUM(order_postings.price * order_postings.quantity) AS total_order_amount')
                                ])
                                ->where('order_postings.reference_code', request()->reference_code)
                                ->where('order_postings.customer_id', auth()->user()->customer_id)
                                ->groupBy([
                                    'reference_code'
                                ])
                                ->first();

        return $this;
    }
}