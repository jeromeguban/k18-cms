<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encryptable;

class Event extends Model
{
    use HasFactory, SoftDeletes, Encryptable;

    protected $table = "events";

    protected $primaryKey = "event_id";

    protected $encryptable = [
        'customer_firstname', 
        'customer_lastname', 
        'email',
        'mobile_no',
    ];

    protected $searchable_fields = [
        'events.event_name',
        'events.type',
        'events.description',
    ];
}
