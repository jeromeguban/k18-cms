<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = "payments";

    protected $hidden = [
        'created_at',
		'updated_at',
		'deleted_at',
    ];


     //Relationships
     public function order()
     {
         return $this->hasMany(Order::class, 'payment_id');
     }

}
