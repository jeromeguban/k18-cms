<?php

namespace App\Processes;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

use App\Models\Payment;
use App\Models\OrderPosting;
use App\Helpers\GuzzleRequest;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Redis;

class HammerPaymentApiProcess
{
    protected $token, $params, $payment;

	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $params = [])
    {
        $this->params = [
            'form_params' => [
                'email'	    =>  'devteam@hmrphils.com',
                'password'  =>  'hmr$$u01',
            ]
        ];

        $this->token = Redis::get('HAMMER_ACCESS_TOKEN');
    }

    /**
     * Execute the handle process.
     *
     * @return void
    */
	public function handle()
	{   
       

        if(!$this->token)
        $this->createOrRetrieveToken();

        if($this->token)
        $this->sendApi();
        

	}

    private function sendApi() 
    {

        $form = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
            ],

            'form_params' => $this->form(),
        ];

        $request = new GuzzleRequest($form);

        $response = $request->post(env('HAMMER_TOKEN_URL').'/api/payments');
        
        $this->payment->update(['is_process' => 1]);

        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));

    }

	private function payments()
	{
        $this->payments = Payment::SelectedFields()
                                ->where('category', "Auction")
                                ->where('is_process', 0)
                                ->get();

		return collect($this->payments);
	}

    private function form(){

        $this->payments()->each(function($payment){


            $payment = Payment::selectedFields()
                        ->where('id', $payment->id)
                        ->where('reference_code', $payment->reference_code)
                        ->first()
                        ->toArray();
                     
            $this->payment =  $payment;

            $orders = OrderPosting::selectedFields()
                            ->joinOrder()
                            ->joinStore()
                            ->joinPosting()
                            ->where('order_postings.reference_code', $payment->reference_code)
                            ->get();

            $order = json_encode($orders->toArray());

            $payment_transactions = PaymentTransaction::selectedFields()
                                        ->where('payment_gateway_reference_code', $payment->reference_code)
                                        ->first()
                                        ->toArray();


            $additional_fields = [

                'order'=>  $order,
                
            ];

            return array_merge( $payment, $payment_transactions, $additional_fields);

		});

    }

      
    public function createOrRetrieveToken()
	{ 
		// if($token = Redis::get('HAMMER_ACCESS_TOKEN'))
		// 	return $token;
    
        $request = new GuzzleRequest($this->params);
        
        $response = $request->post(env('HAMMER_TOKEN_URL').'/auth/access-token/generate');

        if($response->getStatusCode() != 200 )
            abort($response->getStatusCode(), 'Authentication Error. Please try again later.');
           
        Redis::set('HAMMER_ACCESS_TOKEN', $request->data()['token']);
   
        return $request->data()['token'];

	}
}