<?php

namespace App\Models\Address;

use App\Models\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    
    protected $connection = 'address';

    public function scopeWhereProvinceName($query, $province_name)
    {
        return $query->whereHas('province', function ($query) use ($province_name) {
            $query->where('provDesc', $province_name);
        });
    }

    public static function getCitymunCode($municipality_name)
    {
        return self::where('cityMunDesc', $municipality_name)->first()->citymunCode;
    }

    // Relationships
    public function province()
    {
        return $this->belongsTo(Province::class, 'provCode', 'provCode');
    }

    public function barangays()
    {
        return $this->hasMany(Barangay::class, 'citymunCode', 'citymunCode');
    }
}
