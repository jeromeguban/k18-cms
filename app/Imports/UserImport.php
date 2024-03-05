<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            User::updateOrCreate([
                'email'       		            => $row['email'],
                'first_name'			        => $row['first_name'],
                'last_name'			            => $row['last_name'],
            ],
            [
                'first_name'			        => $row['first_name'],
                'phone'		                    => $row['phone'],
                'password' 		                => $row['password'],
                'remember_token'     		    => $row['remember_token'],
                'created_by'     		        => auth()->id(),
                'modified_by'    		        => auth()->id(),
            ]);
        }
    }
}

