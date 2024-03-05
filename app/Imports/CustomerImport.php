<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Customer::create([
                'customer_id'			        => $row['customer_id'],
                'customer_firstname'			=> $row['customer_firstname'],
                'customer_firstname_index'		=> $row['customer_firstname_index'],
                'customer_lastname'       		=> $row['customer_lastname'],
                'customer_lastname_index' 		=> $row['customer_lastname_index'],
                'customer_middlename'     		=> $row['customer_middlename'],
                'customer_suffixname'     		=> $row['customer_suffixname'],
                'customer_company_name'   		=> $row['customer_company_name'],
                'customer_gender'         		=> $row['customer_gender'],
                'customer_birthdate'      		=> $row['customer_birthdate'],
                'customer_phone'          		=> $row['customer_phone'],
                'sms_notification'          	=> $row['sms_notification'],
                'newsletter'          		    => $row['newsletter'],
                'currency_key'          		=> $row['currency_key'],
                'sms_notification'          	=> $row['sms_notification'],
                'customer_created_by'     		=> auth()->id(),
                'customer_modified_by'    		=> auth()->id(),
            ]);
        }
    }
}

