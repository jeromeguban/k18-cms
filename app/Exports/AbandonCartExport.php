<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use App\Models\Cart;
use Illuminate\Contracts\View\View;

class AbandonCartExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = Cart::selectedFields()
            ->joinPosting()
            ->joinPostingItem()
            ->joinCustomer()
            ->joinCustomerLoginCredential()
            ->where('carts.category', 'Retail')
            ->whereBetween('carts.expires_at', [Carbon::parse(request()->from . '00:00:00')->toDateTimeString(), Carbon::parse(request()->to . '23:59:59')->toDateTimeString()])
            ->where('carts.store_id', session()->get('store_id'))
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
        return 'excel.abandon-carts';
    }
}
