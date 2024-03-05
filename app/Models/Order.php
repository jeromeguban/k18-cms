<?php

namespace App\Models;
use App\Traits\Encryptable;
use Illuminate\Support\Facades\DB;

class Order extends Model
{

    use Encryptable;

    protected $table = "orders";


    protected $encryptable = [
        'customer_firstname',
        'customer_lastname',
        'email',
        'mobile_no',
        'customer_name',
        'contact_number',
    ];

    protected $searchable_fields = [
        'customers.customer_firstname_index',
        'customers.customer_lastname_index',
        'customer_login_credentials.email_index',
        'customer_login_credentials.mobile_no_index',
        'orders.payment_status',
        'orders.reference_code',
        'orders.payment_id',
        'orders.checkout_method',
    ];


    // Join

    public function scopeJoinPaymentTypes($query)
	{
		$query->addSelect([
		  'payment_types.name as payment_name',
		]);

		$query->join('payment_types', 'payment_types.id', '=', 'orders.payment_type_id');

		return $query;
	}


    public function scopeJoinCustomer($query)
	{
		$query->addSelect([
		  'customers.customer_firstname',
		  'customers.customer_lastname',
		  'customers.created_at as customer_created_at',
        //   'customers.customer_id',
		]);

		$query->join('customers', 'customers.customer_id', '=', 'orders.customer_id');

		return $query;
	}

    public function scopeJoinLoginCredential($query)
    {
        $query->addSelect([
            // 'customer_login_credentials.email',
            'customer_login_credentials.mobile_no',
        ]);

        $query->leftJoin('customer_login_credentials', 'customers.customer_id', '=', 'customer_login_credentials.customer_id');

        $query->groupBy('customer_login_credentials.mobile_no');
        return $query;

    }

    public function scopeWhereQueryScopes($query)
    {
        if(request()->status == 'Pickup'){
            $query->where('checkout_method', '=', 'Pickup');
        }
        if(request()->status == 'Delivery'){
            $query->where('checkout_method', '=', 'Delivery');
        }
        if(request()->status == 'Transacted'){
            $query->whereNotNull('payment_id');
        }
        if(request()->status == 'Untransacted'){
            $query->whereNull('payment_id');
        }
        if(request()->status == 'Delivery' && request()->tracking_status == 'none'){
            $query->where('checkout_method', '=', 'Delivery');
            $query->where('order_status', '!=', 'Cancelled');
            $query->whereNotNull('courier_details');
            $query->Where('waybill_tracking_number', null);
            // $query->whereNotNull('payment_id');
        }
    }

    public function scopeLeftJoinAddresses($query)
    {
        $query->addSelect([
            DB::raw("CONCAT(IF(addresses.additional_information IS NULL, '', addresses.additional_information),' ',
                    IF (addresses.barangay IS NULL, '', addresses.barangay),' ',
                    IF (addresses.city IS NULL, '', addresses.city),' ',
                    IF (addresses.province IS NULL, '',addresses.province),' ',
                    IF (addresses.zipcode IS NULL, '',addresses.zipcode),' ',
                    IF (addresses.country IS NULL, '',addresses.country)) AS address")
        ]);

        $query->leftjoin('addresses', 'addresses.address_id', '=', 'orders.address_id');

        return $query;

    }

    public function scopeJoinOrderPosting($query)
	{
		$query->addSelect([
            \DB::raw('SUM(order_postings.price * order_postings.quantity) AS sub_total_amount'),
            \DB::raw('SUM(order_postings.discount_price) AS total_discount_price'),
            \DB::raw('SUM((order_postings.price * order_postings.quantity) - order_postings.discount_price) AS total_order_amount'),
            'order_postings.category',
        ]);

		$query->join('order_postings', 'order_postings.order_id', '=', 'orders.id');

        $query->groupBy([
            'order_postings.category',
            'order_postings.order_id',
		]);
	}


    public function scopeJoinStore($query)
	{
		$query->addSelect([
			'stores.reference_id as store_reference_id',
            'stores.store_company_code',
            'stores.store_name',
		]);

		$query->join('stores', 'stores.id', '=', 'orders.store_id');

	}

    public function scopeWhereNotYetPaid($query)
    {
        $query->whereNull('orders.payment_id');
    }



    public function notYetPaid()
    {
    	return !$this->payment_id;
    }

    public function alreadyPaid()
    {
    	return $this->payment_id;
    }


    // Relations
    public function orderPosting()
    {
        return $this->hasOne(OrderPosting::class, 'order_id');
    }

    public function payment()
    {
      return $this->belongsTo(Payment::class, 'payment_id');
    }

}


