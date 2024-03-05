<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\Address;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerAddressesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Address::create([
                'customer_id'            => $row['customer_id'],
                'contact_person'         => $row['contact_person'],
                'contact_number'         => $row['contact_number'],
                'country'                => $row['country'],
                'province'               => $row['province'],
                'city'                   => $row['city'],
                'barangay'               => $row['barangay'],
                'zipcode'                => $row['zipcode'],
                'additional_information' => $row['additional_information'] == 'NULL' ? null : $row['additional_information'],
            ]);
        }
    }
}

