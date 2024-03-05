<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Models\WaybillStatus;
use App\Jobs\Seller\OrderJob;

class ShipmatesCallbackController extends Controller
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
            'courier_status'            => 'required',
            'tracking_number'   => 'required',
        ]);

        $order = Order::where('waybill_tracking_number', request()->tracking_number)
                    ->first();

        if(!$order)
           return "Invalid Tracking Number";

        $store = Store::where('id', $order->store_id)
           ->first();

        $order->update([
            'waybill_status'    => $request->courier_status,
        ]);
        
        
        if($store->store_company_code == 'Seller') {
            dispatch(new OrderJob($order->refresh()));
        }


        WaybillStatus::create([
            'tracking_number'       => $order->waybill_tracking_number,
            'order_id'              => $order->id,
            'courier_id'            => $order->courier_id,
            'status'                => $request->courier_status,
            'transaction_response'  => json_encode($request->all())
        ]);

        return [
            'success'   => 1,
        ];
    }
}
