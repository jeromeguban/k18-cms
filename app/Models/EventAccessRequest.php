<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAccessRequest extends Model
{
    use HasFactory, Encryptable, SoftDeletes;

    protected $encryptable = [
        'customer_firstname', 
        'customer_lastname', 
        'email',
        'mobile_no',
    ];

       
    protected $searchable_fields = [
        'customers.customer_firstname_index', 
        'customers.customer_lastname_index', 
        'customer_login_credentials.email_index',
        'customer_login_credentials.mobile_no_index', 
    ];


    public function scopeJoinCustomer($query)
    {
        $query->addSelect([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customers.created_at as created_date'
        ]);
        
        $query->join('customers', 'customers.customer_id', '=', 'event_access_requests.customer_id');
    }


    public function scopeJoinCustomerLoginCredential($query)
    {
        $query->addSelect([
            'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);
        
        $query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'event_access_requests.customer_id');
    }

    public function scopeJoinEvent($query)
    {
        $query->addSelect([
            'events.event_name'
        ]);

        $query->join('events', 'events.event_id', '=', 'event_access_requests.event_id');
    }

    
    public function scopeWhereQueryScopes($query)
    {
        
        if(request()->filter == "Event" && request()->event_id)
            $query->where('event_access_requests.event_id',request()->event_id);

    }

    // Relationships
    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
