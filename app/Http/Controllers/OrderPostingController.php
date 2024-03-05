<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPosting;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;

class OrderPostingController extends Controller
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
    public function index( Order $order )
    {
        $this->authorize('list', Order::class);

        $query = OrderPosting::where('order_id', $order->id)
                ->selectedFields()
                ->joinOrder()
                ->joinStore()
                ->joinPosting()
                ->joinCustomer()
                ->withRelations()
                ->orderBy('order_postings.created_at', 'desc');
                
        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($customer) {
            $customer =   (new ModelDecrypter)->decryptModel($customer);
        });

        return $response;
    }

    public function getOrderPostingDetails( Order $order)
    {
        $this->authorize('list', Order::class);

        $query = OrderPosting::where('order_id', $order->id)
                            ->searchable()
                            ->sortable();

        return $this->response($query);
    }
}
