<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use App\Models\Order;
use Illuminate\Contracts\View\View;

class OrderSummaryExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];
        
        $query = Order::selectedFields()
        ->joinPaymentTypes()
        ->joinCustomer()
        ->joinStore()
        ->joinLoginCredential()
        ->joinOrderPosting()
        ->whereQueryScopes()
        ->withRelations()
        ->searchable()
        ->where('orders.store_id', session()->get('store_id'))
        ->whereBetween('orders.created_at', [Carbon::parse(request()->from . '00:00:00')->toDateTimeString(), Carbon::parse(request()->to . '23:59:59')->toDateTimeString()])
        // ->when(request()->store == null , function($query) {
        //     $query->where('orders.store_id', session()->get('store_id'));
        // })
        // ->when(request()->store != 'ALL', function($query) {
        //     $query->where('orders.store_id', request()->store);
        // })
        ->get();

        $data['items'] = $query;

        $data['items'] = (new ModelDecrypter)->decryptCollection($query);


        $data = [
            'items' => $query
        ];


        return $data;
    }

    public function getTemplate()
    {
        return 'excel.order-report';
    }
}
