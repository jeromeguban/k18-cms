<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GuzzleRequest;
use App\Http\Requests\ShipmateRequest;
use App\Processes\ShipmateProcess;
use App\Models\OrderWayBill;
use App\Models\Order;
use Carbon\Carbon;

class ShipmateController extends Controller
{
    public function getShippingRates()
    {
        $form = [
            'headers'   => [
                'Accept'    => 'application/json',
                'X-Shipmates-Token' => env('SHIP_MATES_TOKEN'),
            ],
            'json'       => $this->shipmatesFormRequest(),
        ];


        $max_retries = 3;
        $retry_count = 0;
        $response = null;

        while($retry_count < $max_retries) {
            try {
                $request = new GuzzleRequest($form);

                $response = $request->post(env('SHIPMATES_SHIPPING_RATES_URL').'/v1/quote');

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


        return $request->data();



    }

    public function store(ShipmateRequest $request, ShipmateProcess $process)
    {

        // abort(401, 'Server Error');

        $process->create();

        activity()
            ->performedOn( $process->order() )
            ->withProperties( $process ->order() )
            ->log('Orderwaybill Created');

        return [
            'success'   => 1,
            'data' => $process->order(),
        ];
    }

    public function update(Request $request , OrderWaybill $order_waybill)
    {

        $order_waybill->update([
            'pickup_date'    => Carbon::parse($request->pickup_date)->toDateTimeString(),
            'delivered_date' => Carbon::parse($request->delivered_date)->toDateTimeString(),
            'remarks'        => $request->remarks
        ]);

        activity()
            ->performedOn( $order_waybill )
            ->withProperties( $order_waybill )
            ->log('Orderwaybill has been updated');

        return [
            'success'   => 1,
            'data' => $order_waybill,
        ];
    }

    public function trackShipment()
    {
        $form = [
            'headers'   => [
                'Accept'    => 'application/json',
                'X-Shipmates-Token' => env('SHIP_MATES_TOKEN'),
            ],
            'json'       =>  [
                'tracking_number' => request()->tracking_number,
            ],
        ];

        $request = new GuzzleRequest($form);

        $response = $request->post(env('SHIPMATES_SHIPPING_RATES_URL').'/v1/track');

        if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));


        if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');


        return $request->data();
    }

    public function getShipment()
    {
        $form = [
            'headers'   => [
                'Accept'    => 'application/json',
                'X-Shipmates-Token' => env('SHIP_MATES_TOKEN'),
            ],
            
        ];

        $request = new GuzzleRequest($form);

        $response = $request->get(env('SHIPMATES_SHIPPING_RATES_URL').'/v1/shipments/'.request()->tracking_number);

        if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));


        if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');


        return $request->data()['waybill_url'];
    }

    public function cancelShipment()
    {
        $query = Order::selectedFields()
                    ->withRelations()
                    ->where('orders.waybill_tracking_number', request()->tracking_number)
                    ->first();

        // if($query->waybill_status != 'pending')
        //     abort(422, 'Oops! Order Status is not Pending');


        $form = [
            'headers'   => [
                'Accept'    => 'application/json',
                'X-Shipmates-Token' => env('SHIP_MATES_TOKEN'),
            ],
            'json'       =>  [
                'tracking_number' => request()->tracking_number,
            ],
        ];

        $request = new GuzzleRequest($form);

        $response = $request->post(env('SHIPMATES_SHIPPING_RATES_URL').'/v1/cancel');

        if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
            abort($response->getStatusCode(), json_encode($request->data()));


        if($response->getStatusCode() == 500)
            abort(403, 'Something went wrong. Please try again.');


        return [
            'success'   =>1
        ];
    }

    private function shipmatesFormRequest()
    {
        $items = json_decode(request()->items);


        $decodedItems = [];

        foreach ($items as $item) {
            $details = json_decode($item->details);

            $decodedItem = [
                'name' => $details->name,
                'description' => $details->name,
                'quantity'  => $item->quantity,
                'price' => (float) $item->price,
            ];
            array_push($decodedItems, $decodedItem);
        }

        $data = [
            'from_address'  => $this->setFromAddressDetails(),
            'to_address'    => $this->setToAddressDetails(),
            'items'         => $decodedItems,
            'amount_total'  => (float) request()->amount_total,
        ];

        return $data;
    }

    private function setFromAddressDetails()
    {
        $from_address = json_decode(request()->from_address);

        return [
            'contact_name'  => $from_address->contact_name,
            'contact_number'    => $from_address->contact_number,
            'address1'    => $from_address->address1,
            'address2'    => $from_address->address2,
            'city'      => $from_address->city,
            'province'    => $from_address->province,
            'country'    => $from_address->country,
            'zip'    => $from_address->zip,
            'latitude'    => $from_address->latitude,
            'longitude'    => $from_address->longitude,
        ];
    }

    private function setToAddressDetails()
    {
        $to_address = json_decode(request()->to_address);

        return [
            'contact_name'  => $to_address->contact_name,
            'contact_number'    => $to_address->contact_number,
            'address1'    => $to_address->address1,
            'address2'    => $to_address->address2,
            'city'      => $to_address->city,
            'province'    => $to_address->province,
            'country'    => $to_address->country,
            'zip'    => $to_address->zip,
            'latitude'    => $to_address->latitude,
            'longitude'    => $to_address->longitude,
        ];
    }

}
