<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{

    use SoftDeletes;

    protected $primaryKey = "brand_id";

    protected $searchable_fields = [
        'brand_name',
        'brand_code'
    ];

}