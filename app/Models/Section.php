<?php

namespace App\Models;


class Section extends Model
{

    protected $searchable_fields = [
        'name',
        'section_label'
    ];

    public function scopeWhereQueryScopes($query)
    {
        if(request()->section_type && request()->sequence) {
            $query->where('section_type', request()->section_type);
            $query->orderBy('sequence', 'ASC');
        }
    }

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
