<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionAccessRequest extends Model
{
    use HasFactory, Encryptable, SoftDeletes;

    protected $encryptable = [
        'customer_firstname', 
        'customer_lastname', 
        'email',
        'mobile_no',
    ];

    public function scopeWhereQueryScopes($query)
    {
        
        if(request()->filter == "New Requests")
            $query->whereNull('auction_access_requests.status');

        if(request()->filter == "Approved")
            $query->where('auction_access_requests.status','=',"Approved");

        if(request()->filter == "Declined")
            $query->where('auction_access_requests.status','=',"Declined");

        if(request()->filter == "Auction" && request()->auction_id)
            $query->where('auction_access_requests.auction_id',request()->auction_id);

    }

    public function scopeJoinCustomer($query)
    {
        $query->addSelect([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customers.created_at as created_date'
        ]);
        
        $query->join('customers', 'customers.customer_id', '=', 'auction_access_requests.customer_id');
    }

     public function scopeJoinCustomerLoginCredential($query)
    {
        $query->addSelect([
            'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);
        
        $query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'auction_access_requests.customer_id');
    }

    public function scopeJoinAuction($query)
    {
        $query->addSelect([
            'auctions.auction_number'
        ]);

        $query->join('auctions', 'auctions.auction_id', '=', 'auction_access_requests.auction_id');
    }

    // Relationships
    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
