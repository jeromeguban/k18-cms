<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Cost;
use App\Models\Payable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PayableExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $payable =  Payable::selectedFields()
            ->leftJoinBank()
            ->leftJoinAccountNumber()
            ->joinCompany()
            ->joinTotalPayables()
            ->where('payables.id', request()->payable_id)
            ->first();

        $payable_stores =  Payable::selectedFields()
            ->joinPayablesPerStore()
            ->where('payables.id', request()->payable_id)
            ->get();

        $payable_items =  Payable::selectedFields()
            ->joinCompany()
            ->joinPayableItems()
            ->where('payables.id', request()->payable_id)
            ->get();

        $costs =  Cost::selectedFields()
            ->joinCompany()
            ->where('costs.payable_id', request()->payable_id)
            ->get();

        $data = [
            'payables' => $payable,
            'payable_stores' => $payable_stores,
            'payable_items' => $payable_items,
            'costs' => $costs
        ];

        return $data;
    }

    public function getTemplate()
    {
        return 'excel.payable';
    }
}
