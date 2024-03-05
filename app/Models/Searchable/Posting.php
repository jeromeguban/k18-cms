<?php

namespace App\Models\Searchable;

use App\Models\Model;
use App\Traits\Elastic;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Posting extends Model
{
    use SoftDeletes, Elastic;
    protected $primaryKey = 'posting_id';

    protected $hidden = [
        'deleted_at',
        'deleted_by',
        'created_at',
        'updated_at',
        'created_by',
        'modified_by'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'postings';
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function isPublished()
    {
        return $this->published_date;
    }

    public function scopeWherePublished($query)
    {
        return $query->whereNotNull('postings.published_date');
    }

}
