<?php

namespace App\Console\Commands\Data;

use App\Models\Courier;

class CourierData
{
    public function createCouriers()
    {
        $couriersData = [
            [
                'name'      => 'Book By HMR',
                'active'    => 1
            ],
            [
                'name'      => 'LBC',
                'active'    => 1,
            ],
            [
                'name'      => 'Grab',
                'active'    => 1,
            ],
            [
                'name'      => 'Lalamove',
                'active'    => 1,
            ],
            [
                'name'      => 'Borzo',
                'active'    => 1,
            ],
        ];

        $createdCouriers = collect();

        foreach ($couriersData as $courierData) {
            $createdCourier = Courier::create(array_merge($courierData, [
                'created_by' => 1,
                'modified_by' => 1,
            ]));

            $createdCouriers->push($createdCourier);
        }

        return $createdCouriers;
    }
}