<?php

namespace App\Console\Commands\Data;

use App\Models\StoreCourier;
use App\Models\Store;

class StoreCourierData
{
    public function createStoreCouriers($createdCouriers)
    {
        $stores = Store::selectedFields()->get();

        $storeCourierDataList = []; 

        foreach ($createdCouriers as $courier) {
            foreach ($stores as $store) {
                $storeCourierDataList[] = [
                    'courier_id' => $courier->id,
                    'store_id'  => $store->id,
                    'active' => 1,
                ];
            }
        }

        StoreCourier::insert($storeCourierDataList);

        return collect($storeCourierDataList);
    }
}