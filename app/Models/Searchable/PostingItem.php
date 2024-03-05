<?php

namespace App\Models\Searchable;

use App\Models\Model;
use App\Traits\Elastic;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostingItem extends Model
{
    use SoftDeletes, Elastic;

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'posting_items';
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

    public function isInStock()
    {
        return $this->quantity > 0;
    }

    public function isPublished()
    {
        return $this->published_date;
    }

}
