<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\StoreAddress;
use App\Models\Address;

class OrderStoreContactController extends Controller
{
    public function index(Order $order)
    {
        $store_address = StoreAddress::where('store_id', $order->store_id)
                            ->get();


        $customer_address = Address::where('address_id', $order->address_id)
                                ->first();

        $merged_data = array_merge(['store_address' => $store_address], ['customer_address' => $customer_address]);

        return $merged_data;
        
    }
}
