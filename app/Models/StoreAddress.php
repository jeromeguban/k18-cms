<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encryptable;

class StoreAddress extends Model
{
    use HasFactory, SoftDeletes, Encryptable;

    protected $primaryKey = "address_id";
    protected $table = 'store_addresses';

    protected $encryptable = [
        'contact_person',
        'contact_number',
        'email',
    ];
}
