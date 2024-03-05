<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerLoginCredential extends Model
{
    use SoftDeletes;

    protected $table = 'customer_login_credentials';

    protected $hidden = [
    'remember_token',
    ];

    protected $guarded = [];


    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function block()
    {
        return $this->update([
            'blocked_date'  => now(),
        ]);
    }

    public function unblock()
    {
        return $this->update([
            'blocked_date'  => null,
        ]);
    }
}
