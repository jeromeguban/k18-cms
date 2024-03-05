<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Processes\RetailOrderUncancellationProcess;
use Illuminate\Http\Request;

class RetailOrderUncancellationController extends Controller
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
        $this->authorize('uncancel', Order::class);

        $query = Order::selectedFields()
            ->addSelect(['orders.id as order_no'])
            ->joinStore()
            ->where('orders.order_status', 'Cancelled')
            ->where('stores.store_company_code', 'HRH')
            ->whereBetween('orders.created_at', ["2022-11-01 00:00:00", now()->toDateTimeString()])
            ->withRelations()
            ->searchable()
            ->sortable();

        if (request()->customer_id) {
            $query->where('orders.customer_id', request()->customer_id);
        }

        return $this->response($query);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Order $order, RetailOrderUncancellationProcess $process)
    {
        $this->authorize('uncancel', Order::class);

        $process->find($order->id)->uncancel();

        activity()
            ->performedOn($order)
            ->withProperties($order)
            ->log('Order has been Uncancelled');

        return [
            'success' => 1,
            'data' => $order,
        ];
    }
}
