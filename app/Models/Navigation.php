<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Navigation extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'property',
    ];

    public function scopeWhereQueryScopes($query)
    {
        if (request()->type == 'header') {
            $query->where('type', 'Header');
        }

        if (request()->type == 'sidebar') {
            $query->where('type', 'Sidebar');
        }

        if (request()->type == 'footer') {
            $query->where('type', 'Footer');
        }
    }

    public function getLastSequence($type)
    {
        $navigation = self::where('type', $type)
            ->orderBy('sequence', 'DESC')
            ->first();

        return $navigation ? ((int) $navigation->sequence + 1) : 1;
    }

    //Mutators
    protected function getPropertyAttribute()
    {
        return json_decode($this->properties);
    }
}
