<?php

namespace App\Models\Searchable;

use App\Models\Model;
use App\Traits\Elastic;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, Elastic;

    protected $primaryKey = "category_id";
    protected $hidden = [   
                            'icon',
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
        return 'categories';
    }
    
}
