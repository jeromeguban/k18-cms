<?php

namespace App\Http\Controllers;

use App\Helpers\ModelDecrypter;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Exports\OrderSummaryExport;

class OrderController extends Controller
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
        // $this->authorize('list', Order::class);

        $query = Order::selectedFields()
            ->joinPaymentTypes()
            ->joinCustomer()
            ->joinStore()
            ->joinLoginCredential()
        // ->leftJoinAddresses()
            ->joinOrderPosting()
            ->whereQueryScopes()
            ->withRelations()
            ->searchable()
            ->orderBy('orders.created_at', 'desc');

        // Select All Data if user has access on all Stores
        // $all_store = $this->authorize('all', Store::class) ? true : false;

        // if (!$all_store) {
        //     $query->where('orders.store_id', session()->get('store_id'));
        // }

        // $query->where('orders.store_id', session()->get('store_id'));
        if (request()->has('store')) {
            if (request()->store === 'all') {
                // Retrieve orders for all stores
            } else {
                // Retrieve orders for the specified store
                $query->where('orders.store_id', request()->store);
            }
        } else {
            // Default to session store ID when no specific filter is selected
            $query->where('orders.store_id', session()->get('store_id'));
        }
        

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($order) {
            $order = (new ModelDecrypter)->decryptModel($order);
        });

        return $response;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('view', Order::class);

        $order = Order::selectedFields()
            ->joinCustomer()
            ->joinLoginCredential()
        // ->leftJoinAddresses()
            ->joinOrderPosting()
            ->withRelations()
            ->where('orders.id', $order->id)
            ->first();

        return (new ModelDecrypter)->decryptModel($order);

    }

    public function exportOrder()
    {
        return \Excel::download(new OrderSummaryExport,
            'Order Report - '.now()->toDateTimeString().'.xlsx');
    }

}
