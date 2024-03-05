<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerResult
{

    public static function query()
    {

        $query = Customer::select([
            'customers.customer_firstname',
            'customers.customer_lastname',
            'customers.created_at',
            'customer_login_credentials.mobile_no',
            'customer_login_credentials.email',
            'addresses.country',
            'addresses.province',
            'addresses.city',
            'addresses.barangay',
            'addresses.zipcode',
            'addresses.additional_information'
        ]);

        $query->leftJoin('customer_login_credentials', 'customer_login_credentials.customer_id', '=', 'customers.customer_id');
        $query->leftJoin('addresses', 'addresses.customer_id', '=', 'customers.customer_id');

        if (request()->from && request()->to) {

            $from = Carbon::parse(request()->from . ' 00:00:00')->toDateTimeString();
            $to = Carbon::parse(request()->to . '23:59:59')->toDateTimeString();

            $query->whereBetween('customers.created_at', [$from, $to]);
        }    


        return $query->get();
    }
}
