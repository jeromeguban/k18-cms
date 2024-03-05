<?php

namespace App\Models\Hammer;

use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $primaryKey = "item_id";

}