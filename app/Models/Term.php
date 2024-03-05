<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    use HasFactory, SoftDeletes;

    protected $searchable_fields = [
        'name', 'terms'
    ];

    protected $appends = [
        'term'
    ];



    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTermAttribute()
    {
        return strip_tags($this->terms);
    }
}
