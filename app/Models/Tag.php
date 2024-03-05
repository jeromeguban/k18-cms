<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $table = "tags";

    protected $searchable_fields = [
        'name',
    ];

    public function scopeWhereQueryScopes($query)
    {
        if(request()->active == 1) {
            $query->where('active', 1);
        }
    }
    
}
