<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'created_at',
		'updated_at',
		'deleted_at',
    ];

}
