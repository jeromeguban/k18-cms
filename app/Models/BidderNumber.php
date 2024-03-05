<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidderNumber extends Model
{   
    use HasFactory, Encryptable, SoftDeletes;
    
    protected $primaryKey = "bidder_number_id";

    protected $encryptable = [
        'customer_firstname', 
        'customer_lastname', 
        'email',
        'mobile_no',
    ];

    public static function getLastestBidderNumber($auction)
    {
        $bidder_number = self::where('auction_id', $auction)
                            ->orderBy('bidder_number', 'DESC')
                            ->first();
                            
        return $bidder_number ? ((int) $bidder_number->bidder_number + 1) : 1;
    }

    
    public function scopeJoinCustomer($query)
    {
    	$query->addSelect([
    		'customers.customer_firstname',
    		'customers.customer_lastname',
            'customers.created_at as created_date'
        ]);
        
    	$query->join('customers', 'customers.customer_id', '=', 'bidder_numbers.customer_id');
    }

     public function scopeJoinCustomerLoginCredential($query)
    {
    	$query->addSelect([
    		'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);
        
    	$query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'customers.customer_id');
    }
}
