<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{   
    use HasFactory, Encryptable, SoftDeletes;
    
    protected $primaryKey = "participant_id";

    protected $encryptable = [
        'customer_firstname', 
        'customer_lastname', 
        'email',
        'mobile_no',
    ];

    
    public function scopeJoinCustomer($query)
    {
    	$query->addSelect([
    		'customers.customer_firstname',
    		'customers.customer_lastname',
            'customers.avatar',
            'customers.created_at as created_date'
        ]);
        
    	$query->join('customers', 'customers.customer_id', '=', 'participants.customer_id');
    }

     public function scopeJoinCustomerLoginCredential($query)
    {
    	$query->addSelect([
    		'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);
        
    	$query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'participants.customer_id');
    }
}
