<?php

namespace App\Models\Hammer;

use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use SoftDeletes;

    protected $primaryKey = "lot_id";

    
}