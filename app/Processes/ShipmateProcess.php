<?php 

namespace App\Processes;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderWayBill;
use Illuminate\Support\Facades\DB;
use App\Helpers\GuzzleRequest;

class ShipmateProcess
{

	protected $request, $shipmate , $order , $order_waybill, $waybill_response;


	 /**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{
        
		$this->request = $request ? (object) $request : request();

          

	}


     public function order()
     {
         return $this->order;
     }


    
	/**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {
        
    	DB::transaction(function(){
    		$this->createShipmateWaybill();
          $this->updateOrderTrackingNumber()
               ->createWaybill();
    	});
        
    }


    /**
     * Create Shipmate Way bill
     *
     * @return void
    */

    public function createShipmateWaybill()
    {
          $form = [
               'headers'   => [
               'Accept'    => 'application/json',
               'X-Shipmates-Token' => env('SHIP_MATES_TOKEN'),
               ],
               'json'       => $this->shipmatesFormRequest(),
          ];


          $max_retries = 5;
          $retry_count = 0;
          $response = null;
          while($retry_count < $max_retries) {
               try {
                    $request = new GuzzleRequest($form);
                    $response = $request->post(env('SHIPMATES_BOOKING_URL').'/v1/book');
   
                   if($response->getStatusCode() === 200) {
                       break;
                   }
               } catch (\Exception $e) {
   
               }
   
               $retry_count ++;
           }

          if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));


          if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');

          
          $this->waybill_response = $request->data();

    }

    private function shipmatesFormRequest()
    {
          $items = $this->request->order_postings;

          $decoded_items = [];
          
          foreach($items as $item) {
               $details = json_decode($item['details'], true);

               $decoded_item = [
                    'name'    => $details['name'],
                    'description' => $details['name'],
                    'quantity' => $item['quantity'],
                    'price' => (float) $item['price'],
               ];

               array_push($decoded_items, $decoded_item);
          }

          $data = [
               'courier_service' => $this->request->courier_service,
               'to_address' => $this->request->to_address,
               'from_address' => $this->request->from_address,
               'items'   => $decoded_items,
               'amount_total' => (float) $this->request->amount_total,
               'amount_cod'   => (float) $this->request->amount_cod,
          ];

          return $data;
    }

    private function updateOrderTrackingNumber()
    {
          $this->order = Order::where('id', $this->request->order_id)
                              ->first();

          
          if($this->order) {
               $this->order->update([
                    'waybill_tracking_number' => $this->waybill_response["data"]["tracking_number"]
               ]);
          }

          return $this;
    }

    private function createWaybill()
    {
          $this->order_waybill = OrderWayBill::updateOrCreate([
               'customer_id' => $this->order->customer_id,
               'order_id' => $this->order->id,
          ],[
               'reference_code' => $this->order->reference_code,
               'tracking_number' => $this->waybill_response["data"]["tracking_number"],
               'transaction_response' => json_encode($this->waybill_response),
          ]);

          return $this;
    }



}