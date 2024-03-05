<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Posting;
use App\Helpers\CacheHelper;
use App\Models\OrderPosting;
use App\Helpers\GuzzleRequest;
use Illuminate\Console\Command;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendPaymentGatewayJob as Job;
use App\Jobs\OrderJob as OrderJob;
class GenerateHammerPaymentApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

        /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      
        $this->reference_code = "HCEO4TX1SFUUTFUW";
     
        dispatch(new OrderJob($this->reference_code));
       
    
    }



//     /**
//      * Execute the console command.
//      *
//      * @return mixed
//      */
//     public function handle()
//     {
      
     
//         $form = [
//             'params' => $this->form(),
//         ];

//         $request = new GuzzleRequest($form);
// dd(  $request );
//         $response = $request->post(env('HAMMER_TOKEN_URL').'/api/auctions/postings');

//         if($response->getStatusCode() != 200)
//         abort($response->getStatusCode(), json_encode($request->data()));
 
//     }


	

//     private function form(){

       
//         $payment = Payment::selectedFields()
//         ->where('reference_code', 'J7WXUTVW6PR3FM1J')
//         ->first();
     
//         $this->payment =  $payment;

//         $order = OrderPosting::selectedFields()
//                     ->joinOrder()
//                     ->joinStore()
//                     ->joinPosting()
//                     ->where('order_postings.reference_code', 'J7WXUTVW6PR3FM1J')
//                     ->get();

     

//         $payment_transactions = PaymentTransaction::selectedFields()
//                                 ->where('reference_code', 'J7WXUTVW6PR3FM1J')
//                                 ->first(); 


//         $data = [

//         'order'=>  $order,
        
//         'payment'=>  $payment,
        
//         'payment_transaction'=>  $payment_transactions,

//         ];

//         return $data;
        
//         // $orders = $data['order'];


//         // foreach ($orders as $index => $data) {
            
//         // $this->comment($orders[$index]->reference_code );
//         // }


      
       
//     }

  


//     private function createOrder() 
//     {

//         $orders = OrderPosting::selectedFields()
//             ->joinOrder()
//             ->joinStore()
//             ->joinPosting()
//             ->where('order_postings.reference_code','J7WXUTVW6PR3FM1J')
//             ->get();




//             $payment_transactions = PaymentTransaction::selectedFields()
//                                     ->where('reference_code','J7WXUTVW6PR3FM1J')
//                                     ->first()
//                                     ->toArray();


//             $order = json_encode($orders->toArray());
          

//             $payment = Payment::selectedFields()
//                  ->where('reference_code','J7WXUTVW6PR3FM1J')
//                 ->first()
//                 ->toArray();

         
//             $additional_fields = [
               
//                 'order'=>  $order,
              
//             ]; 
    

//         $data = array_merge( $payment, $payment_transactions, $additional_fields);

//         // dd($data['order']);
      
//     //   $arrayLength = count($datas);

//     //   $i = 0;
//     //   while ($i < $arrayLength)
//     //   {
//     //     $this->comment('Successfully Uncancelled Lots!');
          
//     //       $i++;
//     //   }




//     $decode = ( json_decode($data['order']));
//       $datas =  $decode;



//    foreach ($datas as $index => $data) {
        
//         $this->comment($datas[$index]->reference_code );
//       }


//     }
    
}
