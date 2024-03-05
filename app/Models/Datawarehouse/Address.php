<?php

namespace App\Models\Datawarehouse;

use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $primaryKey = "address_id";

    
}