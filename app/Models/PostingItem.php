<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PostingItem extends Model
{
    use SoftDeletes;

    protected $appends = [
        'extended_desc',
        'desc'
    ];

    protected $table = "posting_items";

    protected $searchable_fields = [
    	'name',
    	'description',
    	'extended_description',
    ];


    public function getExtendedDescAttribute()
    {
        return strip_tags($this->extended_description);
    }

    public function getDescAttribute()
    {
        return strip_tags($this->description);
    }

}
