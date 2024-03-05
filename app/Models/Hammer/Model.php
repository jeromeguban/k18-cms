<?php

namespace App\Models\Hammer;

use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
	use Pagination;
 	protected $connection = 'hammer';
 	protected $guarded = [];

 	public function scopeSelectedFields($query)
    {
    	return $query->select([ "{$this->getTable()}.*" ]);
    }
    

}