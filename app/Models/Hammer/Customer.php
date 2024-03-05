<?php

namespace App\Models\Hammer;

use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $primaryKey = "customer_id";

    
}