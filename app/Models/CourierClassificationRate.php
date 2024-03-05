<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CourierClassificationRate extends Model
{
    use SoftDeletes;


    public function scopeJoinOriginClassification($query)
    {
        $query->addSelect([
            'origin_classifcations.classification_name AS origin_classifcation_name'
        ]);

        $query->join('address.classifications AS origin_classifcations', 'origin_classifcations.id', '=', 'courier_classification_rates.origin_classification_id');
    }

    public function scopeJoinDestinationClassification($query)
    {
        $query->addSelect([
            'destination_classifcations.classification_name AS destination_classifcation_name'
        ]);

        $query->join('address.classifications AS destination_classifcations', 'destination_classifcations.id', '=', 'courier_classification_rates.destination_classification_id');
    }
}
