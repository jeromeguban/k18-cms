<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes,Encryptable;

    protected $primaryKey = "customer_id";

    protected $searchable_fields = [
        'customers.customer_firstname_index', 
        'customers.customer_lastname_index', 
        'customer_login_credentials.email_index',
        'customer_login_credentials.mobile_no_index', 
        'customers.created_at', 
    ];

    protected $encryptable = [
        'customer_firstname', 
        'customer_lastname', 
        'customer_middlename',
        'customer_suffixname',
        'customer_company_name',
        'mobile_no',
        'customer_phone',
        'username',
        'email',
    ];

    public function scopeJoinLoginCredential($query)
    {
        $query->addSelect([ 
            'customer_login_credentials.username', 
            'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',  
            'customer_login_credentials.confirmation_date',
            'customer_login_credentials.is_verified',
            'customer_login_credentials.id as login_credential_id',
            'customer_login_credentials.blocked_date',
            'customer_login_credentials.password'
        ]);

        $query->leftJoin('customer_login_credentials', 'customers.customer_id', '=', 'customer_login_credentials.customer_id');

        return $query;

    }

    public function scopeJoinAddress($query) 
    {
        $query->addSelect([ 
            'addresses.country', 
            'addresses.province', 
            'addresses.city', 
            'addresses.barangay', 
            'addresses.zipcode', 
            'addresses.additional_information'
        ]);
        $query->leftJoin('addresses', function($join){
            $join->on('customers.customer_id', '=', 'addresses.address_id')
                 ->where('addresses.address_id', '=', 'customers.customer_id');
        });

        return $query;
    }


    public function scopeWhereQueryScopes($query)
    {
            if(request()->filter && request()->filter == 'Last Name')
                $query->where('customers.customer_lastname_index', md5(strtoupper(request()->q)));

            if(request()->filter && request()->filter == 'First Name')
                $query->where('customers.customer_firstname_index', md5(strtoupper(request()->q)));

            if(request()->filter && request()->filter == 'Email')
                $query->where('customer_login_credentials.email_index', md5(request()->q));

            if(request()->filter && request()->filter == 'Mobile No.')
                $query->where('customer_login_credentials.mobile_no_index', md5(request()->q));
            
            if(request()->from && request()->to){
                
                    $from = Carbon::parse(request()->from.' 00:00:00')->toDateTimeString();
                    $to = Carbon::parse(request()->to.'23:59:59')->toDateTimeString();

                    $query->whereBetween('customers.created_at', [$from, $to]);
            }         
      


        return $query;
    }



    //Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
    public function loginCredential()
    {
        return $this->hasOne(CustomerLoginCredential::class, 'customer_id', 'customer_id');
    }
    public function address()
    {
        return $this->HasMany(Address::class, 'address_id', 'address_id');
    }
}
