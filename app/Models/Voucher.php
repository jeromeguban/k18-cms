<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $table = "vouchers";

    protected $searchable_fields = [
        'name',
        'code',
        'type',
        'expiration_date',
        'limit',
        'total_usage',
        'category',
    ];

      public function scopeLeftJoinTerms($query) 
    {
        $query->addSelect([
            'terms.name as term_name',
        ]);

        $query->leftJoin('terms', 'terms.id', 'vouchers.term_id');
    }
    
}
