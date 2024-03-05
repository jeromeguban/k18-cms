<?php

namespace App\Models\Elastic;

use App\Models\Model;
use App\Traits\Elastic;

class PostingItem extends Model
{
    use Elastic;

    protected $primaryKey = "id";
    protected $hidden = [   
                            'created_by',
                            'modified_by',
                            'created_at',
                            'updated_at',
                            'deleted_at'
                       ];

    
    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'posting_items';
    }
    
}
