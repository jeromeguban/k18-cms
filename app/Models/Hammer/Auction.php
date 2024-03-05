<?php

namespace App\Models\Hammer;

use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use SoftDeletes;

    protected $primaryKey = "auction_id";

    
}