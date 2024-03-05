<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AccountNumber extends Model
{
    use SoftDeletes;

    public function scopeJoinBank($query)
    {
        $query->addSelect([
            'account_numbers.*',
            'banks.name',
        ]);

        $query->join('banks', 'account_numbers.bank_id', '=', 'banks.id');

        return $query;
    }
}
