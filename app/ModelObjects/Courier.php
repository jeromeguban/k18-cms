<?php

namespace App\ModelObjects;

use App\Models\Store;

class Courier
{
    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function syncStoreCouriers(array $couriers)
    {


        $this->store->storeCouriers()->delete();

        foreach ($couriers as $courier) {
            $this->store->storeCouriers()->create([
                'courier_id'    => is_array($courier) ? $courier['id'] : $courier
            ]);
        }
    }
}
