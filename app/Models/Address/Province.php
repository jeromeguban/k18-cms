<?php

namespace App\Models\Address;

use App\Models\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    protected $connection = 'address';

    // Relationships
    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'provCode', 'provCode');
    }
}
