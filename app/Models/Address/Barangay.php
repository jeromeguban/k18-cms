<?php

namespace App\Models\Address;

use App\Models\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barangay extends Model
{
    protected $connection = 'address';

    public function scopeWhereMunicipalityName($query, $municipality_name)
    {
        $citymunCode = Municipality::getCitymunCode($municipality_name);
        return $query->where('citymunCode', $citymunCode);
    }

    // Relationships
    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'citymunCode', 'citymunCode');
    }
}
