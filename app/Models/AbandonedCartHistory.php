<?php

namespace App\Models;

use App\Traits\Encryptable;

class AbandonedCartHistory extends Model
{
    use Encryptable;

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

    public function scopeJoinStore($query)
    {
        $query->addSelect([
            'stores.code',
            'stores.store_company_name',
            'stores.store_name',
            'stores.store_company_code',
            'stores.address_line',
        ]);

        $query->join('stores', 'stores.id', '=', 'abandoned_cart_histories.store_id');
    }

    public function scopeJoinCustomer($query)
    {
        $query->addSelect([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customers.created_at as created_date',
        ]);

        $query->join('customers', 'customers.customer_id', '=', 'abandoned_cart_histories.customer_id');
    }

    public function scopeJoinCustomerLoginCredential($query)
    {
        $query->addSelect([
            'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);

        $query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'abandoned_cart_histories.customer_id');
    }

}
