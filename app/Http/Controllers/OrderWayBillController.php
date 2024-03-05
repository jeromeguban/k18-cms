<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderWayBill;
use App\Models\Order;
use App\Models\WaybillStatus;
use App\Helpers\ModelDecrypter;
use App\Helpers\GuzzleRequest;

class OrderWayBillController extends Controller
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
    public function index()
    {
        $this->authorize('list', OrderWayBill::class);

        $query = OrderWayBill::selectedFields()
                            ->joinOrder()
                            ->joinStore()
                            // ->joinWaybillStatus()
                            ->withRelations()
                            ->sortable()
                            // ->where('orders.store_id', session()->get('store_id'))
                            ->searchable();

        if (request()->has('store')) {
            if (request()->store === 'all') {
            } else {
                
                $query->where('orders.store_id', request()->store);
            }
        } else {
            
            $query->where('orders.store_id', session()->get('store_id'));
        }

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($order_waybill) {
            $order_waybill = (new ModelDecrypter)->decryptModel($order_waybill);
        });

        return $response;
    }

    public function updateStatus(Request $request, OrderWayBill $order_waybill)
    {
        $order = Order::where('id', $request->order_id)
                    ->firstOrFail();

        $order->update([
            'waybill_status'    => $request->status,
        ]);

        $order_waybill->update([
            'delivery_status_id'    => $request->delivery_status_id
        ]);

        WaybillStatus::create([
            'order_id'              => $request->order_id,
            'tracking_number'       => $request->tracking_number,
            'status'                => $request->status,
            'transaction_response'  => json_encode($request->all())
        ]);

        return [
            'success'   => 1
        ];

    }

 
}
