<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Support\Facades\DB;

class Cart extends Model
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
        'postings.name',
        'postings.description',
        'carts.created_at',
        'carts.referral_code',
    ];

    public function scopeJoinPosting($query)
    {
        $query->addSelect([
            'postings.auction_number',
            'postings.name',
            'postings.slug',
            'postings.banner',
            'postings.description',
            'postings.lot_number',
            DB::raw('carts.price * carts.quantity as total_price'),
        ]);

        $query->join('postings', 'carts.posting_id', '=', 'postings.posting_id');
    }

    public function scopeJoinStore($query)
    {
        $query->addSelect([
            'stores.code',
            'stores.store_company_name',
            'stores.store_name',
            'stores.store_company_code',
            'stores.address_line',
        ]);

        $query->join('stores', 'carts.store_id', '=', 'stores.id');
    }

    public function scopeJoinCustomer($query)
    {
        $query->addSelect([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customers.created_at as created_date',
        ]);

        $query->join('customers', 'customers.customer_id', '=', 'carts.customer_id');
    }

    public function scopeJoinCustomerLoginCredential($query)
    {
        $query->addSelect([
            'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);

        $query->join('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'carts.customer_id');
    }

    public function scopeJoinPostingItem($query)
    {
        $query->addSelect([
            'posting_items.quantity AS posting_quantity',
        ]);

        $query->join('posting_items', 'posting_items.id', '=', 'carts.posting_item_id');
    }

}
