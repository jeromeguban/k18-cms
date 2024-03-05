<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;
use Illuminate\Support\Facades\DB;

class BidderDeposit extends Model
{
    use HasFactory, Encryptable;

    protected $encryptable = [
        'customer_firstname',
        'customer_lastname',
        'email',
    ];

    protected $searchable_fields = [
        'customers.customer_firstname_index',
        'customers.customer_lastname_index',
        'customer_login_credentials.email_index',
        'customer_login_credentials.mobile_no_index',
        'bidder_deposits.reference_code'
    ];


    public function scopeJoinCustomer($query)
    {
        $query->addSelect([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customer_login_credentials.email'

        ]);

        $query->join('customers', 'bidder_deposits.customer_id', '=', 'customers.customer_id');
        $query->join('customer_login_credentials', 'customers.customer_id', '=', 'customer_login_credentials.customer_id');


        return $query;
    }

    // public function scopeJoinCustomerLoginCredential($query)
    // {
    //     $query->addSelect([
    //         'customer_login_credentials.email'
    //     ]);

    //     $query->join('customer_login_credentials', 'customers.customer_id', '=', 'customer_login_credentials.customer_id');
    // }

    public function scopeJoinPaymentType($query)
    {
        $query->addSelect([
            'payment_types.name as payment_name'
        ]);

        $query->join('payment_types', 'bidder_deposits.payment_type_id', '=', 'payment_types.id');
    }

    public function scopeJoinCreatedBy($query)
    {
        $query->addSelect([
             DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS created_by_name")
        ]);

        $query->leftJoin('users', 'bidder_deposits.created_by', '=', 'users.id');

        return $query;
    }

    public function scopeWherePaid($query)
    {
        $query->where('payment_status', 'Paid');
    }

    public function scopeWhereDeposit($query)
    {
        $query->where('status', 'Deposit');
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
