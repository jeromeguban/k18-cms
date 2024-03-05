<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\CustomerLoginCredential;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerLoginCredentialImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            CustomerLoginCredential::create([
                'customer_id'	    => $row['customer_id'],
                'email'             => $row['email'],
                'email_index'       => $row['email_index'],
                'username'          => $row['username'],
                'username_index'    => $row['username_index'],
                'mobile_no'         => $row['mobile_no'],
                'password'          => !$row['password'] ? bcrypt('123456789') : $row['password'],
                'mobile_no_index'   => $row['mobile_no_index'],
                'confirmation_date' => $row['confirmation_date'],
                'mobile_verification_date'  => $row['mobile_verification_date'],
                'is_verified'               => $row['is_verified'],
                'blocked_date'              => $row['blocked_date'] == 'NULL' ? null :  $row['blocked_date'],
                'registration_token'        => $row['registration_token'],
                'remember_token'            => $row['remember_token'],
            ]);
        }
    }
}

