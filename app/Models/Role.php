<?php

namespace App\Models;

use App\Traits\Pagination;
use Kodeine\Acl\Models\Eloquent\Role as Model;

class Role extends Model
{
    use Pagination;

    protected $searchable_fields = [
        'name',
        'slug',
        'description'
    ];
}
