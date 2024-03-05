<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $primaryKey = "address_id";
    protected $table = 'addresses';

    protected $fillable = [ 
        'contact_person',
        'contact_number',
        'country', 
        'province',
        'city',
        'barangay',
        'zipcode',
        'customer_id'
    ];

    protected $hidden = [
        'created_at',
        'address_id',
        'address_created_by',
        'address_modified_by'
    ];

    // Relationships
    public function addressable()
    {
        return $this->morphTo();
    }
}
