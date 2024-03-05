<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliateMarketing extends Model 
{
    use SoftDeletes;

     protected $searchable_fields = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
    ];

    // Relations
    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }


    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}



